package com.indylan.common.extensions

import android.app.Activity
import android.widget.EditText
import androidx.core.view.ViewCompat
import androidx.core.view.WindowInsetsCompat

/**
 * Show KeyBoard.
 */
fun EditText.showKeyBoard() {
    requestFocus()
    val insetsController = ViewCompat.getWindowInsetsController(this)
    insetsController?.show(WindowInsetsCompat.Type.ime())
}

/**
 * Hide KeyBoard.
 */
fun Activity.hideKeyBoard() {
    val insetsController = ViewCompat.getWindowInsetsController(window.decorView)
    insetsController?.hide(WindowInsetsCompat.Type.ime())
}
