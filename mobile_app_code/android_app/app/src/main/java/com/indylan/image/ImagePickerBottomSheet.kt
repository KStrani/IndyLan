package com.indylan.image

import android.app.Dialog
import android.content.Intent
import android.graphics.Color
import android.graphics.Paint
import android.graphics.drawable.ColorDrawable
import android.os.Build
import android.os.Bundle
import android.util.TypedValue
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.activity.result.contract.ActivityResultContracts
import androidx.appcompat.widget.LinearLayoutCompat
import androidx.core.content.ContextCompat
import androidx.core.content.FileProvider
import androidx.core.os.bundleOf
import androidx.fragment.app.setFragmentResult
import com.google.android.material.bottomsheet.BottomSheetBehavior
import com.google.android.material.bottomsheet.BottomSheetDialog
import com.google.android.material.bottomsheet.BottomSheetDialogFragment
import com.google.android.material.shape.MaterialShapeDrawable
import com.google.android.material.shape.ShapeAppearanceModel
import com.google.android.material.textview.MaterialTextView
import com.indylan.BuildConfig
import com.indylan.R
import com.indylan.common.extensions.hideKeyBoard
import java.io.File
import java.io.Serializable

class ImagePickerBottomSheet : BottomSheetDialogFragment(), View.OnClickListener {

    private val options by lazy {
        val options = arguments?.getStringArray("options")
        options?.map { enumValueOf<ImagePickerOptions>(it) }
    }

    private val cameraImage by lazy {
        FileProvider.getUriForFile(
            requireContext(),
            "${BuildConfig.APPLICATION_ID}.provider",
            File(requireContext().cacheDir, "image_${System.currentTimeMillis()}.jpg")
        )
    }
    private val cameraVideo by lazy {
        FileProvider.getUriForFile(
            requireContext(),
            "${BuildConfig.APPLICATION_ID}.provider",
            File(requireContext().cacheDir, "video_${System.currentTimeMillis()}.mp4")
        )
    }
    private val captureVideo =
        registerForActivityResult(ActivityResultContracts.CaptureVideo()) { result ->
            dismissAllowingStateLoss()
            if (result) {
                setFragmentResult(
                    KEY_VIDEO, bundleOf(
                        KEY_IS_SUCCESS to true,
                        KEY_URI to cameraVideo,
                    )
                )
            } else {
                setFragmentResult(
                    KEY_IMAGE, bundleOf(
                        KEY_IS_SUCCESS to false,
                        KEY_MESSAGE to "Unable to capture video",
                    )
                )
            }
        }
    private val captureImage =
        registerForActivityResult(ActivityResultContracts.TakePicture()) { result ->
            dismissAllowingStateLoss()
            if (result) {
                setFragmentResult(
                    KEY_IMAGE, bundleOf(
                        KEY_IS_SUCCESS to true,
                        KEY_URI to cameraImage,
                    )
                )
            } else {
                setFragmentResult(
                    KEY_IMAGE, bundleOf(
                        KEY_IS_SUCCESS to false,
                        KEY_MESSAGE to "Unable to capture image",
                    )
                )
            }
        }
    private val galleryImage =
        registerForActivityResult(ActivityResultContracts.OpenDocument()) { result ->
            dismissAllowingStateLoss()
            if (result != null) {
                context?.contentResolver?.takePersistableUriPermission(
                    result,
                    Intent.FLAG_GRANT_READ_URI_PERMISSION
                )
                setFragmentResult(
                    "image", bundleOf(
                        KEY_IS_SUCCESS to true,
                        KEY_URI to result,
                    )
                )
            } else {
                setFragmentResult(
                    KEY_IMAGE, bundleOf(
                        KEY_IS_SUCCESS to false,
                        KEY_MESSAGE to "Unable to get image",
                    )
                )
            }
        }

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setStyle(STYLE_NORMAL, R.style.BottomSheetDialogStyle)
    }

    override fun onCreateDialog(savedInstanceState: Bundle?): Dialog {
        val dialog = super.onCreateDialog(savedInstanceState)

        if (dialog is BottomSheetDialog) {
            dialog.behavior.skipCollapsed = true
            dialog.behavior.state = BottomSheetBehavior.STATE_EXPANDED
        }

        dialog.setOnShowListener {
            (view?.parent as? ViewGroup)?.background = ColorDrawable(Color.TRANSPARENT)
            // in 6.0.1 keyboard opens automatically
            hideKeyBoard()
        }

        return dialog
    }

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View {
        val linear = LinearLayoutCompat(requireContext())
        val parameters = LinearLayoutCompat.LayoutParams(
            ViewGroup.LayoutParams.MATCH_PARENT,
            ViewGroup.LayoutParams.WRAP_CONTENT
        )
        val corner = (resources.displayMetrics.density * 20)
        val backgroundDrawable = MaterialShapeDrawable(
            ShapeAppearanceModel.builder()
                .setTopLeftCornerSize(corner)
                .setTopRightCornerSize(corner)
                .build()
        ).apply {
            with(TypedValue()) {
                context?.theme?.resolveAttribute(
                    com.indylan.R.attr.colorSurface,
                    this,
                    true
                )
                setTint(ContextCompat.getColor(requireContext(), resourceId))
            }
            paintStyle = Paint.Style.FILL
        }
        linear.background = backgroundDrawable
        linear.layoutParams = parameters
        linear.orientation = LinearLayoutCompat.VERTICAL
        options?.forEach {
            val text = MaterialTextView(requireContext())
            val parametersText = LinearLayoutCompat.LayoutParams(
                ViewGroup.LayoutParams.MATCH_PARENT,
                ViewGroup.LayoutParams.WRAP_CONTENT
            )
            text.layoutParams = parametersText
            text.text = it.value
            text.tag = it
            with(TypedValue()) {
                context?.theme?.resolveAttribute(
                    R.attr.selectableItemBackground,
                    this,
                    true
                )
                text.setBackgroundResource(resourceId)
            }
            with(TypedValue()) {
                context?.theme?.resolveAttribute(
                    R.attr.textAppearanceBody1,
                    this,
                    true
                )
                if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.M) {
                    text.setTextAppearance(resourceId)
                } else {
                    text.setTextAppearance(requireContext(), resourceId)
                }
            }
            val padding = (resources.displayMetrics.density * 15).toInt()
            text.setPadding(padding, padding, padding, padding)
            text.setOnClickListener(this)
            linear.addView(text)
        }
        return linear
    }

    override fun onClick(v: View?) {
        when (v?.tag as? ImagePickerOptions) {
            ImagePickerOptions.Camera -> {
                captureImage.launch(cameraImage)
            }
            ImagePickerOptions.Gallery -> {
                galleryImage.launch(listOf("image/*").toTypedArray())
            }
            ImagePickerOptions.Video -> {
                captureVideo.launch(cameraVideo)
            }
            ImagePickerOptions.Remove -> {
                dismissAllowingStateLoss()
                setFragmentResult(
                    KEY_IMAGE, bundleOf(
                        "isRemove" to true
                    )
                )
            }
            ImagePickerOptions.Cancel -> {
                dismissAllowingStateLoss()
                setFragmentResult(
                    KEY_IMAGE, bundleOf(
                        KEY_IS_SUCCESS to false,
                        KEY_MESSAGE to "",
                    )
                )
            }
            else -> {
                dismissAllowingStateLoss()
            }
        }
    }

    companion object {

        const val KEY_IMAGE = "image"
        const val KEY_VIDEO = "video"
        const val KEY_IS_SUCCESS = "isSuccess"
        const val KEY_URI = "uri"
        const val KEY_MESSAGE = "message"

        fun showImagePicker(
            options: List<ImagePickerOptions>
        ): ImagePickerBottomSheet {
            val bottomSheet = ImagePickerBottomSheet()
            val bundle = Bundle()
            bundle.putStringArray("options", options.map { it.name }.toTypedArray())
            bottomSheet.arguments = bundle
            return bottomSheet
        }
    }

    enum class ImagePickerOptions(val value: String) : Serializable {
        Camera("Capture from Camera"),
        Gallery("Pick from Gallery"),
        Video("Capture Video"),
        Remove("Remove Image"),
        Cancel("Cancel")
    }
}
