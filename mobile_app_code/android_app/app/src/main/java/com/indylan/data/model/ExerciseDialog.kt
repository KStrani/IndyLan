package com.indylan.data.model

import android.os.Parcelable
import com.google.gson.annotations.SerializedName
import kotlinx.parcelize.Parcelize

@Parcelize
data class ExerciseDialog(
    @SerializedName("dialogue_master_id")
    val dialogueMasterId: String? = null,
    @SerializedName("title")
    val title: String? = null,
    @SerializedName("full_audio")
    val audio: String? = null,
    @SerializedName("is_audio_available")
    val isAudioAvailable: String? = null,
    @SerializedName("list")
    val list: List<DialogList>? = null
) : Parcelable


@Parcelize
data class DialogList(
    @SerializedName("phrase")
    val phrase: String? = null,
    @SerializedName("audio_name")
    val audio: String? = null,
    @SerializedName("is_audio_available")
    val isAudioAvailable: String? = null,
    @SerializedName("speaker")
    val speaker: String? = null,
    @SerializedName("sequence_no")
    val sequence: String? = null,
    var isCorrect: Boolean = false
) : Parcelable {
    fun parseSpeaker(): Int {
        return speaker?.toIntOrNull() ?: 0
    }

    fun parseSequence(): Int {
        return sequence?.toIntOrNull() ?: 0
    }

    fun fixPhrase(): String? {
        return phrase?.replace("$", ".")
    }
}