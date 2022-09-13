package com.indylan.data.model

import android.os.Parcelable
import com.google.gson.annotations.SerializedName
import kotlinx.parcelize.Parcelize

@Parcelize
data class ExerciseMatchAnswer(
    @SerializedName("word_id")
    val id: String? = null,
    @SerializedName("option")
    val option: List<OptionMatch>? = null,
    @SerializedName("option1")
    val option1: List<OptionMatch>? = null
) : Parcelable

@Parcelize
data class OptionMatch(
    @SerializedName("word_id")
    val wordId: String? = null,
    @SerializedName("word_s")
    val wordS: String? = null,
    @SerializedName("word_t")
    val wordT: String? = null,
    @SerializedName("word")
    val word: String? = null
) : Parcelable