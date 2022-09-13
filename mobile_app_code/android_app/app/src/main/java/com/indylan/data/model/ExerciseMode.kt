package com.indylan.data.model

import android.os.Parcelable
import com.google.gson.annotations.SerializedName
import kotlinx.parcelize.Parcelize

@Parcelize
data class ExerciseMode(
    @SerializedName("id")
    val id: String? = null,
    @SerializedName("mode_name")
    val name: String? = null,
    @SerializedName("icon")
    val icon: String? = null
) : Parcelable {
    fun parseExerciseMode(): String {
        return when (id?.toIntOrNull()) {
            1 -> "vocabulary_exercise"
            2 -> "dialogues_exercise"
            3 -> "phrases_exercise"
            4 -> "grammar_exercise"
            5 -> "culture_exercise"
            6 -> "aural_exercise"
            else -> ""
        }
    }

    fun isTest(): Boolean {
        return id == "7"
    }
}