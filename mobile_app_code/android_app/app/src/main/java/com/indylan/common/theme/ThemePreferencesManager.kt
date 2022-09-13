package com.indylan.common.theme

import android.content.Context
import androidx.appcompat.app.AppCompatDelegate
import com.google.android.material.dialog.MaterialAlertDialogBuilder
import com.indylan.data.preferences.PreferenceStorage
import javax.inject.Inject
import javax.inject.Singleton

@Singleton
class ThemePreferencesManager @Inject constructor(
    private val preferenceStorage: PreferenceStorage
) {

    companion object {
        private val THEME_OPTIONS = arrayOf(
            "System default",
            "Light",
            "Dark",
            "Battery saver"
        )
        private val THEME_OPTIONS_VALUE = arrayOf(
            AppCompatDelegate.MODE_NIGHT_FOLLOW_SYSTEM,
            AppCompatDelegate.MODE_NIGHT_NO,
            AppCompatDelegate.MODE_NIGHT_YES,
            AppCompatDelegate.MODE_NIGHT_AUTO_BATTERY
        )
    }

    fun showChooseThemeDialog(context: Context) {
        val dialog = MaterialAlertDialogBuilder(context)
        dialog.setSingleChoiceItems(
            THEME_OPTIONS,
            THEME_OPTIONS_VALUE.indexOf(preferenceStorage.nightMode)
        ) { dialogInterface, position ->
            dialogInterface.dismiss()
            preferenceStorage.nightMode = THEME_OPTIONS_VALUE[position]
            AppCompatDelegate.setDefaultNightMode(THEME_OPTIONS_VALUE[position])
        }
        dialog.show()
    }

    fun applyTheme() {
        AppCompatDelegate.setDefaultNightMode(preferenceStorage.nightMode)
    }
}