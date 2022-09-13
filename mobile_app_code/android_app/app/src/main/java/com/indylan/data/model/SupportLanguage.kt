package com.indylan.data.model

import android.os.Parcelable
import com.google.gson.annotations.SerializedName
import kotlinx.parcelize.Parcelize

@Parcelize
data class SupportLanguage(
    @SerializedName("support_lang_id")
    val id: String? = null,
    @SerializedName("lang_name")
    val name: String? = null,
    @SerializedName("lang_code")
    val code: String? = null,
    @SerializedName("field_name")
    val field: String? = null,
    @SerializedName("icon")
    val icon: String? = null
) : Parcelable {
    fun correctCountryCode(): String? {
        //return code
        return when (code) {
            "sv" -> "sw"
            "nb" -> "nrw"
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