package com.indylan.common.extensions

import android.content.Context
import android.content.res.Resources
import android.net.Uri
import android.text.Editable
import android.text.TextWatcher
import android.util.TypedValue
import android.view.inputmethod.InputMethodManager
import android.webkit.MimeTypeMap
import androidx.annotation.AttrRes
import androidx.annotation.ColorInt
import androidx.annotation.ColorRes
import androidx.appcompat.widget.AppCompatEditText
import androidx.core.content.ContextCompat
import androidx.fragment.app.DialogFragment
import androidx.fragment.app.Fragment
import com.indylan.BuildConfig
import kotlinx.coroutines.*
import timber.log.Timber
import java.io.File
import java.io.FileInputStream
import java.io.FileOutputStream

/**
 * Helper to force a when statement to assert all options are matched in a when statement.
 *
 * By default, Kotlin doesn't care if all branches are handled in a when statement. However, if you
 * use the when statement as an expression (with a value) it will force all cases to be handled.
 *
 * This helper is to make a lightweight way to say you meant to match all of them.
 *
 * Usage:
 *
 * ```
 * when(sealedObject) {
 *     is OneType -> //
 *     is AnotherType -> //
 * }.checkAllMatched
 */
val <T> T.checkAllMatched: T
    get() = this

// region UI utils

/**
 * Retrieves a color from the theme by attributes. If the attribute is not defined, a fall back
 * color will be returned.
 */
@ColorInt
fun Context.getThemeColor(
    @AttrRes attrResId: Int,
    @ColorRes fallbackColorResId: Int
): Int {
    val tv = TypedValue()
    return if (theme.resolveAttribute(attrResId, tv, true)) {
        tv.data
    } else {
        ContextCompat.getColor(this, fallbackColorResId)
    }
}

// endregion

/**
 * Helper to throw exceptions only in Debug builds, logging a warning otherwise.
 */
fun exceptionInDebug(t: Throwable) {
    if (BuildConfig.DEBUG) {
        throw t
    } else {
        Timber.e(t)
    }
}

val Int.dp: Int
    get() = (this / Resources.getSystem().displayMetrics.density).toInt()
val Int.px: Int
    get() = (this * Resources.getSystem().displayMetrics.density).toInt()


/**
 * Show KeyBoard.
 */
fun Fragment.showKeyBoard() {
    val view = this.activity?.currentFocus
    if (view != null) {
        val inputManager =
            this.activity?.getSystemService(Context.INPUT_METHOD_SERVICE) as InputMethodManager
        inputManager.showSoftInput(view, InputMethodManager.SHOW_IMPLICIT)
    }
}

/**
 * Hide KeyBoard.
 */
fun Fragment.hideKeyBoard() {
    val view = this.activity?.currentFocus
    if (view != null) {
        val inputManager =
            this.activity?.getSystemService(Context.INPUT_METHOD_SERVICE) as InputMethodManager
        inputManager.hideSoftInputFromWindow(view.windowToken, InputMethodManager.HIDE_NOT_ALWAYS)
    }
}

/**
 * Show KeyBoard.
 */
fun DialogFragment.showKeyBoard() {
    val view = dialog?.currentFocus
    val inputManager =
        activity?.getSystemService(Context.INPUT_METHOD_SERVICE) as InputMethodManager?
    inputManager?.showSoftInput(view, InputMethodManager.SHOW_IMPLICIT)
}

/**
 * Hide KeyBoard.
 */
fun DialogFragment.hideKeyBoard() {
    val view = dialog?.currentFocus
    val inputManager =
        activity?.getSystemService(Context.INPUT_METHOD_SERVICE) as InputMethodManager?
    inputManager?.hideSoftInputFromWindow(view?.windowToken, InputMethodManager.HIDE_NOT_ALWAYS)
}

fun AppCompatEditText.afterTextChangedDebounce(delayMillis: Long, input: (String) -> Unit) {
    var lastInput = ""
    var debounceJob: Job? = null
    val uiScope = CoroutineScope(Dispatchers.Main + SupervisorJob())
    this.addTextChangedListener(object : TextWatcher {
        override fun afterTextChanged(editable: Editable?) {
            if (editable != null) {
                val newtInput = editable.toString()
                debounceJob?.cancel()
                if (lastInput != newtInput) {
                    lastInput = newtInput
                    debounceJob = uiScope.launch {
                        delay(delayMillis)
                        if (lastInput == newtInput) {
                            input(newtInput)
                        }
                    }
                }
            }
        }

        override fun beforeTextChanged(cs: CharSequence?, start: Int, count: Int, after: Int) {}
        override fun onTextChanged(cs: CharSequence?, start: Int, before: Int, count: Int) {}
    })
}

fun String.isValidEmail(): Boolean {
    return android.util.Patterns.EMAIL_ADDRESS.matcher(this).matches()
}

fun String.getMimeType(): String {
    var type = "image/jpeg" // Default Value
    val extension = MimeTypeMap.getFileExtensionFromUrl(this)
    if (extension != null) {
        type = MimeTypeMap.getSingleton().getMimeTypeFromExtension(extension) ?: type
    }
    return type
}

fun String.isValidImageFile(): Boolean {
    return this.endsWith(".jpg", true)
            || this.endsWith(".jpeg", true)
            || this.endsWith(".png", true)
            || this.endsWith(".gif", true)
}

fun Uri.copyFileInCache(context: Context, fileName: String): File? {
    return context.contentResolver.openFileDescriptor(this, "r", null)?.let {
        val inputStream = FileInputStream(it.fileDescriptor)
        val file = File(context.cacheDir, fileName)
        val outputStream = FileOutputStream(file)
        inputStream.copyTo(outputStream)
        file
    }
}
