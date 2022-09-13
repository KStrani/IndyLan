package com.indylan.data.model

import android.os.Parcelable
import com.google.gson.annotations.SerializedName
import kotlinx.parcelize.Parcelize

@Parcelize
data class ExerciseVerb(
    @SerializedName("word_id")
    val id: String? = null,
    @SerializedName("image_file")
    val imageName: String? = null,
    @SerializedName("word")
    val word: String? = null,
    @SerializedName("word_english")
    val wordEnglish: String? = null,
    @SerializedName("audio_file")
    val audio: String? = null,
    @SerializedName("image_path")
    val image: String? = null,
    @SerializedName("option")
    val option: String? = null
) : Parcelable