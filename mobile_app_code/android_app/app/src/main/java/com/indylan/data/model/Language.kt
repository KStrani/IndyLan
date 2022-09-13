package com.indylan.data.model

import android.os.Parcelable
import com.google.gson.annotations.SerializedName
import kotlinx.parcelize.Parcelize

@Parcelize
data class Language(
    @SerializedName("source_language_id")
    val id: String? = null,
    @SerializedName("language_name")
    val name: String? = null,
    @SerializedName("language_code")
    val code: String? = null,
    @SerializedName("field_name")
    val field: String? = null,
    @SerializedName("icon")
    val icon: String? = null,
    @SerializedName("image")
    val image: String? = null
) : Parcelable {
    fun correctCountryCode(): String? {
        return when (code) {
            "est" -> "et"
            "cr" -> "hr"
            "chi" -> "zh"
            "sor" -> "ku"
            "pu" -> "pa"
            "ukr" -> "uk"
            "alb" -> "sq"
            else -> code
        }
    }
}