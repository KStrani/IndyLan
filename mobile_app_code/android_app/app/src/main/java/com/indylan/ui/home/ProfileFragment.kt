package com.indylan.ui.home

import android.net.Uri
import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.View.GONE
import android.view.View.VISIBLE
import android.view.ViewGroup
import android.view.inputmethod.EditorInfo
import androidx.core.view.isVisible
import androidx.fragment.app.activityViewModels
import androidx.navigation.fragment.findNavController
import com.indylan.R
import com.indylan.common.extensions.goneView
import com.indylan.common.extensions.hideKeyBoard
import com.indylan.common.extensions.openPlayStore
import com.indylan.common.extensions.showView
import com.indylan.common.glide.GlideApp
import com.indylan.data.model.result.EventObserver
import com.indylan.databinding.FragmentProfileBinding
import com.indylan.databinding.LayoutErrorBinding
import com.indylan.databinding.LayoutProgressBinding
import com.indylan.image.CropSquareImageContract
import com.indylan.image.ImagePickerBottomSheet
import com.indylan.ui.base.BaseFragment
import com.indylan.ui.base.BaseViewModel
import dagger.hilt.android.AndroidEntryPoint
import timber.log.Timber

@AndroidEntryPoint
class ProfileFragment : BaseFragment() {

    private val viewModel: HomeViewModel by activityViewModels()
    private lateinit var binding: FragmentProfileBinding
    private val cropImageResult = registerForActivityResult(CropSquareImageContract()) { imageUri ->
        if (imageUri != null) {
            Timber.d(imageUri.toString())
            GlideApp.with(this).load(imageUri).into(binding.imageViewProfile)
            viewModel.editProfile(
                binding.textInputEditTextName.text.toString(),
                binding.textInputEditTextEmail.text.toString(),
                "",
                "",
                "",
                "0",
                imageUri
            )
        } else {
            showMessage("Unable to crop image")
        }
    }

    override fun getViewModel(): BaseViewModel = viewModel

    override fun onBackPress(): Boolean = true

    override fun getLoadingView(): LayoutProgressBinding? = null

    override fun getErrorView(): LayoutErrorBinding? = null

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        childFragmentManager.setFragmentResultListener(
            ImagePickerBottomSheet.KEY_IMAGE,
            this
        ) { requestKey: String, bundle: Bundle ->
            Timber.d("$requestKey $bundle")
            if (requestKey == ImagePickerBottomSheet.KEY_IMAGE) {
                val isSuccess = bundle.getBoolean(ImagePickerBottomSheet.KEY_IS_SUCCESS)
                if (isSuccess) {
                    val uri = bundle.getParcelable<Uri>(ImagePickerBottomSheet.KEY_URI)
                    uri?.let {
                        cropImageResult.launch(it)
                    }
                } else {
                    showMessage(bundle.getString(ImagePickerBottomSheet.KEY_MESSAGE))
                }
            }
        }
    }

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View {
        binding = FragmentProfileBinding.inflate(inflater, container, false).apply {
            lifecycleOwner = viewLifecycleOwner
            imageViewProfile.setOnClickListener {
                ImagePickerBottomSheet.showImagePicker(
                    listOf(
                        ImagePickerBottomSheet.ImagePickerOptions.Camera,
                        ImagePickerBottomSheet.ImagePickerOptions.Gallery
                    )
                ).show(childFragmentManager, "ImagePicker")
            }
            buttonChangePassword.setOnClickListener {
                if (textInputEditTextCurrentPassword.visibility == GONE) {
                    textInputEditTextCurrentPassword.showView()
                    textInputEditTextNewPassword.showView()
                    textInputEditTextConfirmPassword.showView()
                    textInputEditTextEmail.imeOptions = EditorInfo.IME_ACTION_NEXT
                    textInputEditTextConfirmPassword.imeOptions = EditorInfo.IME_ACTION_DONE
                } else {
                    textInputEditTextCurrentPassword.goneView()
                    textInputEditTextNewPassword.goneView()
                    textInputEditTextConfirmPassword.goneView()
                    textInputEditTextEmail.imeOptions = EditorInfo.IME_ACTION_DONE
                }
            }
            buttonUpdate.setOnClickListener {
                hideKeyBoard()
                val updatePassword = textInputEditTextCurrentPassword.visibility == VISIBLE
                viewModel.editProfile(
                    textInputEditTextName.text.toString(),
                    textInputEditTextEmail.text.toString(),
                    if (updatePassword) textInputEditTextCurrentPassword.text.toString() else "",
                    if (updatePassword) textInputEditTextNewPassword.text.toString() else "",
                    if (updatePassword) textInputEditTextConfirmPassword.text.toString() else "",
                    "0", null
                )
            }
            buttonRateUs.setOnClickListener {
                requireActivity().openPlayStore()
            }
            buttonAboutUs.setOnClickListener {
                findNavController().navigate(ProfileFragmentDirections.toAboutUsFragment())
            }
            textInputEditTextEmail.setOnEditorActionListener { _, actionId, _ ->
                if (actionId == EditorInfo.IME_ACTION_DONE) {
                    buttonUpdate.callOnClick()
                    return@setOnEditorActionListener true
                }
                return@setOnEditorActionListener false
            }
            textInputEditTextConfirmPassword.setOnEditorActionListener { _, actionId, _ ->
                if (actionId == EditorInfo.IME_ACTION_DONE) {
                    buttonUpdate.callOnClick()
                    return@setOnEditorActionListener true
                }
                return@setOnEditorActionListener false
            }
        }
        return binding.root
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)
        setTitle(getString(R.string.profile), showLogout = true, showProfile = false)
        viewModel.userInfoLiveData.observe(viewLifecycleOwner, EventObserver {
            binding.textInputEditTextName.setText(String.format("%s", it.first_name))
            binding.textInputEditTextEmail.setText(it.email)
            binding.textInputEditTextTotalScore.text =
                getString(R.string.total_score_d, it.parseScore())
            binding.buttonChangePassword.isVisible = it.social_type == "0"
            val profileImage = if (!it.social_pic.isNullOrEmpty()) {
                it.social_pic
            } else if (!it.profile_pic.isNullOrEmpty()) {
                it.profile_pic
            } else {
                null
            }
            profileImage?.let {
                GlideApp.with(this).load(it).into(binding.imageViewProfile)
            }
        })
        viewModel.getUser()
    }
}