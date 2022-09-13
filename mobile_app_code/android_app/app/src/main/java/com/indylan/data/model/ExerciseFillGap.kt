package com.indylan.data.model

import android.os.Parcelable
import com.google.gson.annotations.SerializedName
import kotlinx.parcelize.Parcelize

@Parcelize
data class ExerciseFillGap(
    @SerializedName("question")
    val question: String? = null,
    @SerializedName("options")
    val options: String? = null,
    @SerializedName("notes")
    val notes: String? = null,
    @SerializedName("is_audio_available")
    val isAudioAvailable: String? = null,
    @SerializedName("audio_file")
    val audio: String? = null
) : Parcelable