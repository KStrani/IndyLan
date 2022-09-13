package com.indylan.data.model

import android.os.Parcelable
import com.google.gson.annotations.SerializedName
import kotlinx.parcelize.Parcelize

@Parcelize
data class ExerciseTextComprehension(
    @SerializedName("culture_master_id")
    val cultureMasterId: String? = null,
    @SerializedName("title_text")
    val title: String? = null,
    @SerializedName("external_link")
    val link: String? = null,
    @SerializedName("paragraph")
    val paragraph: String? = null,
    @SerializedName("image_name")
    val imageName: String? = null,
    @SerializedName("image_path")
    val image: String? = null,
    @SerializedName("questions")
    val questions: List<ExerciseTranslation>? = null
) : Parcelable