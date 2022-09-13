package com.indylan.data.model

import android.os.Parcelable
import kotlinx.parcelize.Parcelize

@Parcelize
data class User(
    val user_id: String? = null,
    val first_name: String? = null,
    val last_name: String? = null,
    val email: String? = null,
    val password: String? = null,
    val type: String? = null,
    val profile_pic: String? = null,
    val social_pic: String? = null,
    val social_id: String? = null,
    val social_type: String? = null,
    val is_active: String? = null,
    val updated_at: String? = null,
    val created_at: String? = null,
    val reset_token: String? = null,
    val os_type: String? = null,
    val score: String? = null
) : Parcelable {
    fun parseScore(): Int {
        return score?.toIntOrNull() ?: 0
    }
}

enum class LoginType(val value: String) {
    NORMAL("0"),
    FACEBOOK("1"),
    GOOGLE("2")
}