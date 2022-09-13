package com.indylan.data.preferences

import android.content.SharedPreferences
import androidx.annotation.WorkerThread
import androidx.core.content.edit
import com.google.gson.Gson
import kotlin.properties.ReadWriteProperty
import kotlin.reflect.KProperty

class BooleanPreference(
    private val preferences: SharedPreferences,
    private val name: String,
    private val defaultValue: Boolean
) : ReadWriteProperty<Any, Boolean> {

    @WorkerThread
    override fun getValue(thisRef: Any, property: KProperty<*>): Boolean {
        return preferences.getBoolean(name, defaultValue)
    }

    override fun setValue(thisRef: Any, property: KProperty<*>, value: Boolean) {
        preferences.edit { putBoolean(name, value) }
    }
}

class IntegerPreference(
    private val preferences: SharedPreferences,
    private val name: String,
    private val defaultValue: Int
) : ReadWriteProperty<Any, Int> {

    @WorkerThread
    override fun getValue(thisRef: Any, property: KProperty<*>): Int {
        return preferences.getInt(name, defaultValue)
    }

    override fun setValue(thisRef: Any, property: KProperty<*>, value: Int) {
        preferences.edit { putInt(name, value) }
    }
}

class ObjectPreference<T>(
    private val preferences: SharedPreferences,
    private val gson: Gson,
    private val name: String,
    private val aclass: Class<T>,
    private val defaultValue: T? = null
) : ReadWriteProperty<Any, T?> {

    @WorkerThread
    override fun getValue(thisRef: Any, property: KProperty<*>): T? {
        val json = preferences.getString(name, "")
        return gson.fromJson(json, aclass) ?: return defaultValue
    }

    override fun setValue(thisRef: Any, property: KProperty<*>, value: T?) {
        preferences.edit(commit = true) {
            if (value == null) {
                remove(name)
            } else {
                val json = gson.toJson(value)
                putString(name, json)
            }
        }
    }
}

class StringPreference(
    private val preferences: SharedPreferences,
    private val name: String,
    private val defaultValue: String?
) : ReadWriteProperty<Any, String?> {

    @WorkerThread
    override fun getValue(thisRef: Any, property: KProperty<*>): String? {
        return preferences.getString(name, defaultValue)
    }

    override fun setValue(thisRef: Any, property: KProperty<*>, value: String?) {
        preferences.edit { putString(name, value) }
    }
}