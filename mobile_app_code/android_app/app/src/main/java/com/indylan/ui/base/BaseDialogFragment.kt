package com.indylan.ui.base

import android.content.res.ColorStateList
import android.graphics.drawable.InsetDrawable
import android.os.Bundle
import android.view.View
import androidx.appcompat.app.AppCompatDialogFragment
import androidx.core.view.ViewCompat
import com.google.android.material.shape.MaterialShapeDrawable
import com.indylan.R
import com.indylan.common.extensions.getThemeColor
import com.indylan.common.extensions.px

abstract class BaseDialogFragment : AppCompatDialogFragment() {

    override fun getTheme(): Int {
        return R.style.ThemeOverlay_MaterialComponents_Dialog_Alert
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)
        fixDialogBackground()
    }

    private fun fixDialogBackground() {
        dialog?.window?.let {
            val draw = MaterialShapeDrawable(
                requireContext(),
                null,
                R.attr.alertDialogStyle,
                R.style.ThemeOverlay_MaterialComponents_Dialog_Alert
            )
            draw.initializeElevationOverlay(context)
            draw.fillColor = ColorStateList.valueOf(
                requireContext().getThemeColor(
                    R.attr.colorSurface,
                    android.R.color.white
                )
            )
            draw.setCornerSize(
                context?.resources?.getDimension(R.dimen.corner) ?: 0f
            )
            draw.elevation = ViewCompat.getElevation(it.decorView)
            it.setBackgroundDrawable(InsetDrawable(draw, 20.px))
        }
    }

    fun showMessage(message: String?) {
        if (context is BaseActivity) {
            (context as BaseActivity).showMessage(message)
        }
    }
}