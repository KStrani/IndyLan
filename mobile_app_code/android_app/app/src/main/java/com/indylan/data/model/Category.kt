package com.indylan.data.model

import android.os.Parcelable
import com.google.gson.annotations.SerializedName
import kotlinx.parcelize.Parcelize

@Parcelize
data class Category(
    @SerializedName("exercise_mode_category_id")
    val id: String? = null,
    @SerializedName("image")
    val imageName: String? = null,
    @SerializedName("category_name")
    val name: String? = null,
    @SerializedName("image_path")
    val image: String? = null
) : Parcelable