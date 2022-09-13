package com.indylan.data.model

import android.os.Parcelable
import com.google.gson.annotations.SerializedName
import kotlinx.parcelize.Parcelize

@Parcelize
data class ExerciseType(
    @SerializedName("id")
    val id: String? = null,
    @SerializedName("type_name")
    val name: String? = null,
    @SerializedName("image")
    val imageName: String? = null,
    @SerializedName("image_path")
    val image: String? = null,
    @SerializedName("total")
    val total: String? = null
) : Parcelable {
    fun parseExerciseType(): ExerciseTypeEnum {
        return ExerciseTypeEnum.getType(id?.toIntOrNull())
    }
}

enum class ExerciseTypeEnum {
    TRANSLATION,
    MULTI_CHOICE_IMAGE,
    MULTI_CHOICE_WORDS,
    CHOOSE_LETTERS,
    WRITE_WORD,
    CHOOSE_IMAGE,
    MATCHING,
    FLASH_CARDS_TEXT,
    FLASH_CARDS_IMAGE,
    LISTENING,
    TEXT_CHAT_VIEW_ONLY,
    MULTIPLE_CHOICE_CHAT_SELECTION,
    FILL_GAP,
    TEXT_COMPREHENSION,
    AURAL_NUMBERS,
    AURAL_SENTENCES,
    AURAL_WORDS,
    UNKNOWN;

    companion object {
        fun getType(type: Int?): ExerciseTypeEnum {
            return when (type) {
                1 -> TRANSLATION
                2 -> MULTI_CHOICE_IMAGE
                3 -> MULTI_CHOICE_WORDS
                4 -> CHOOSE_LETTERS
                5 -> WRITE_WORD
                6 -> CHOOSE_IMAGE
                7 -> MATCHING
                8 -> FLASH_CARDS_IMAGE
                9 -> LISTENING
                10 -> MULTI_CHOICE_WORDS
                11 -> FILL_GAP
                12 -> FLASH_CARDS_TEXT
                13 -> TEXT_CHAT_VIEW_ONLY
                14 -> MULTIPLE_CHOICE_CHAT_SELECTION
                15 -> TEXT_COMPREHENSION
                16 -> AURAL_NUMBERS
                17 -> AURAL_SENTENCES
                18 -> AURAL_WORDS
                else -> UNKNOWN
            }
        }
    }
}