package com.indylan.common.extensions

import android.app.Activity
import android.os.Build
import android.view.MotionEvent
import android.view.View
import android.view.WindowInsetsController
import android.view.animation.AnimationUtils
import androidx.core.view.WindowCompat
import androidx.interpolator.view.animation.FastOutSlowInInterpolator
import com.indylan.BuildConfig
import com.indylan.R

fun View.showView() {
    visibility = View.VISIBLE
}

fun View.hideView() {
    visibility = View.INVISIBLE
}

fun View.goneView() {
    visibility = View.GONE
}

fun Activity.setSystemUiLightStatusBar(isLightStatusBar: Boolean) {
    if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.M) {
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.R) {
            val systemUiAppearance = if (isLightStatusBar) {
                WindowInsetsController.APPEARANCE_LIGHT_STATUS_BARS
            } else {
                0
            }
            window.insetsController?.setSystemBarsAppearance(
                systemUiAppearance,
                WindowInsetsController.APPEARANCE_LIGHT_STATUS_BARS
            )
        } else {
            /*val systemUiVisibilityFlags = if (isLightStatusBar) {
                window.decorView.systemUiVisibility or View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR
            } else {
                window.decorView.systemUiVisibility and View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR.inv()
            }
            window.decorView.systemUiVisibility = systemUiVisibilityFlags*/
            WindowCompat.getInsetsController(
                window,
                window.decorView
            )?.isAppearanceLightStatusBars = isLightStatusBar
        }
    }
}

fun Activity.setSystemUiLightNavigationBar(isLightNavigationBar: Boolean) {
    if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.R) {
            val systemUiAppearance = if (isLightNavigationBar) {
                WindowInsetsController.APPEARANCE_LIGHT_NAVIGATION_BARS
            } else {
                0
            }
            window.insetsController?.setSystemBarsAppearance(
                systemUiAppearance,
                WindowInsetsController.APPEARANCE_LIGHT_NAVIGATION_BARS
            )
        } else {
            /*val systemUiVisibilityFlags = if (isLightNavigationBar) {
                window.decorView.systemUiVisibility or View.SYSTEM_UI_FLAG_LIGHT_NAVIGATION_BAR
            } else {
                window.decorView.systemUiVisibility and View.SYSTEM_UI_FLAG_LIGHT_NAVIGATION_BAR.inv()
            }
            window.decorView.systemUiVisibility = systemUiVisibilityFlags*/
            WindowCompat.getInsetsController(
                window,
                window.decorView
            )?.isAppearanceLightNavigationBars = isLightNavigationBar
        }
    }
}

fun View.setAsButton(enabled: Boolean = true) {
    if (BuildConfig.BUTTON_PRESS_EFFECT) {
        val interpolator = FastOutSlowInInterpolator()
        val animationPress = AnimationUtils.loadAnimation(context, R.anim.button_press)
        val animationRelease = AnimationUtils.loadAnimation(context, R.anim.button_release)
        if (enabled) {
            setOnTouchListener { v, event ->
                when (event.action) {
                    MotionEvent.ACTION_DOWN -> {
                        /*v.animate()
                            .scaleX(0.95f)
                            .scaleY(0.95f)
                            .setInterpolator(FastOutSlowInInterpolator())
                            .setDuration(150L)*/
                        animationPress.interpolator = interpolator
                        v.startAnimation(animationPress)
                    }
                    else -> {
                        /*v.animate()
                            .scaleX(1f)
                            .scaleY(1f)
                            .setInterpolator(FastOutSlowInInterpolator())
                            .setDuration(150L)*/
                        animationRelease.interpolator = interpolator
                        v.startAnimation(animationRelease)
                    }
                }
                return@setOnTouchListener false
            }
        } else {
            setOnTouchListener(null)
        }
    }
}