package com.indylan.data.model

import android.os.Parcelable
import com.google.gson.annotations.SerializedName
import kotlinx.parcelize.Parcelize

@Parcelize
data class ExerciseTranslation(
    @SerializedName("word_id")
    val id: String? = null,
    @SerializedName("word")
    val word: String? = null,
    @SerializedName("notes")
    val notes: String? = null,
    @SerializedName("is_audio_available")
    val isAudioAvailable: String? = null,
    @SerializedName("audio_file")
    val audio: String? = null,
    @SerializedName("image_file")
    val imageName: String? = null,
    @SerializedName("image_path")
    val image: String? = null,
    @SerializedName("option")
    val option: List<OptionTranslation>? = null
) : Parcelable

@Parcelize
data class OptionTranslation(
    @SerializedName("word")
    val word: String? = null,
    @SerializedName("is_correct")
    val isCorrect: Int? = null
) : Parcelable