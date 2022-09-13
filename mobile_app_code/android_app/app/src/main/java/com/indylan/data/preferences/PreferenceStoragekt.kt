package com.indylan.data.preferences

import android.content.Context
import android.content.Context.MODE_PRIVATE
import androidx.appcompat.app.AppCompatDelegate
import com.google.gson.Gson
import com.indylan.BuildConfig
import com.indylan.data.model.User
import dagger.hilt.android.qualifiers.ApplicationContext
import javax.inject.Inject

class SharedPreferenceStorage @Inject constructor(
    @ApplicationContext context: Context,
    gson: Gson
) : PreferenceStorage {

    private val prefs = context.getSharedPreferences(BuildConfig.PREF_NAME, MODE_PRIVATE)

    override var token by StringPreference(prefs, PREF_TOKEN, "0")

    override var user by ObjectPreference(prefs, gson, PREF_USER, User::class.java, null)

    override var nightMode by IntegerPreference(
        prefs,
        PREF_NIGHT_MODE,
        AppCompatDelegate.MODE_NIGHT_NO
    )

    companion object {
        const val PREF_TOKEN = "pref_token"
        const val PREF_USER = "pref_user"
        const val PREF_NIGHT_MODE = "pref_night_mode"
    }
}