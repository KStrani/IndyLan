package com.indylan.data.model

import android.os.Parcelable
import com.google.gson.annotations.SerializedName
import kotlinx.parcelize.Parcelize

@Parcelize
data class ExercisePictureAnswer(
    @SerializedName("word_id")
    val id: String? = null,
    @SerializedName("image_path")
    val image: String? = null,
    @SerializedName("image_file")
    val imageName: String? = null,
    @SerializedName("word")
    val word: String? = null,
    @SerializedName("word_english")
    val wordEnglish: String? = null,
    @SerializedName("audio_file")
    val audio: String? = null,
    @SerializedName("is_audio_available")
    val isAudioAvailable: String? = null,
    @SerializedName("option")
    val option: List<OptionPictureAnswer>? = null
) : Parcelable

@Parcelize
data class OptionPictureAnswer(
    @SerializedName("subcategory_id")
    val subcategoryId: String? = null,
    @SerializedName("image_file")
    val imageName: String? = null,
    @SerializedName("image_path")
    val image: String? = null,
    @SerializedName("is_correct")
    val isCorrect: Int? = null
) : Parcelable