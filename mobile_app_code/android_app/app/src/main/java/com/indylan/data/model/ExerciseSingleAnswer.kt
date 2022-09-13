package com.indylan.data.model

import android.os.Parcelable
import com.google.gson.annotations.SerializedName
import kotlinx.parcelize.Parcelize

@Parcelize
data class ExerciseSingleAnswer(
    @SerializedName("word_id")
    val wordId: String? = null,
    @SerializedName("phrases_id")
    val phrasesId: String? = null,
    @SerializedName("image_file")
    val imageName: String? = null,
    @SerializedName("word")
    val word: String? = null,
    @SerializedName("word_english")
    val wordEnglish: String? = null,
    @SerializedName("phrase_en")
    val phraseEnglish: String? = null,
    @SerializedName("audio_file")
    val audio: String? = null,
    @SerializedName("is_audio_available")
    val isAudioAvailable: String? = null,
    @SerializedName("image_path")
    val image: String? = null,
    @SerializedName("option")
    val option: String? = null
) : Parcelable