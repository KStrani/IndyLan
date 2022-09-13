package com.indylan.image

import android.app.Activity
import android.content.Context
import android.content.Intent
import android.net.Uri
import androidx.activity.result.contract.ActivityResultContract
import androidx.core.content.ContextCompat
import com.indylan.R
import com.indylan.common.extensions.copyFileInCache
import com.indylan.common.extensions.getThemeColor
import com.yalantis.ucrop.UCrop
import java.io.File

class CropSquareImageContract : ActivityResultContract<Uri, Uri?>() {

    override fun createIntent(context: Context, input: Uri): Intent {
        val options = UCrop.Options()
        options.setCompressionQuality(100)
        // applying UI theme
        options.setStatusBarColor(context.getThemeColor(R.attr.colorPrimary, R.color.primary))
        options.setToolbarColor(context.getThemeColor(R.attr.colorPrimary, R.color.primary))
        options.setToolbarWidgetColor(ContextCompat.getColor(context, android.R.color.white))
        options.setFreeStyleCropEnabled(false)
        options.withAspectRatio(1f, 1f)
        options.withMaxResultSize(500, 500)

        val fileName = when {
            input.path.isNullOrEmpty() -> "cropped_image_${System.currentTimeMillis()}.jpg"
            File(input.path.toString()).name.isNullOrEmpty() -> "cropped_image_${System.currentTimeMillis()}.jpg"
            else -> "cropped_image_${System.currentTimeMillis()}.jpg"
            //else -> "cropped_image_" + File(input.path.toString()).name
        }
        val file = input.copyFileInCache(context, fileName)
        val newUri = Uri.fromFile(file)

        return UCrop.of(newUri, newUri).withOptions(options).getIntent(context)
    }

    override fun parseResult(resultCode: Int, intent: Intent?): Uri? {
        return when (resultCode) {
            Activity.RESULT_OK -> {
                if (intent != null) {
                    val resultUri = UCrop.getOutput(intent)
                    resultUri
                } else {
                    null
                }
            }
            else -> {
                intent?.let {
                    val cropError = UCrop.getError(it)
                    cropError?.printStackTrace()
                }
                null
            }
        }
    }
}
