package com.indylan.data.model

import android.os.Parcelable
import com.google.gson.annotations.SerializedName
import kotlinx.parcelize.Parcelize

@Parcelize
data class Subcategory(
    @SerializedName("exercise_mode_subcategory_id")
    val id: String? = null,
    @SerializedName("category_id")
    val categoryId: String? = null,
    @SerializedName("difficulty_level_id")
    val difficultyLevelId: String? = null,
    @SerializedName("image")
    val imageName: String? = null,
    @SerializedName("subcategory_name")
    val name: String? = null,
    @SerializedName("image_path")
    val image: String? = null,
    @SerializedName("ratting")
    val rating: Int = 0
) : Parcelable