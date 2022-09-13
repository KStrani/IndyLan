package com.indylan.data.model

import android.os.Parcelable
import com.google.gson.annotations.SerializedName
import kotlinx.parcelize.Parcelize

@Parcelize
data class Option(
    @SerializedName("subcategory_id")
    val subcategoryId: String? = null,
    @SerializedName("image_file")
    val imageName: String? = null,
    @SerializedName("image_path")
    val image: String? = null,
    @SerializedName("word")
    val word: String? = null,
    @SerializedName("is_correct")
    val isCorrect: Int? = null
) : Parcelable