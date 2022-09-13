package com.indylan.data

import com.google.gson.Gson
import com.google.gson.TypeAdapter
import com.google.gson.reflect.TypeToken
import com.google.gson.stream.JsonReader
import com.google.gson.stream.JsonToken
import com.google.gson.stream.JsonWriter
import com.indylan.data.model.Option

class StringTypeAdapter : TypeAdapter<String>() {

    override fun read(`in`: JsonReader?): String? {
        val gson = Gson()
        return when (`in`?.peek()) {
            JsonToken.BEGIN_ARRAY -> {
                val options =
                    gson.fromJson<List<Option>>(`in`, object : TypeToken<List<Option>>() {}.type)
                gson.toJson(options)
            }
            JsonToken.END_ARRAY -> {
                ""
            }
            JsonToken.BEGIN_OBJECT -> {
                ""
            }
            JsonToken.END_OBJECT -> {
                ""
            }
            JsonToken.NAME -> {
                ""
            }
            JsonToken.STRING -> {
                `in`.nextString()
            }
            JsonToken.NUMBER -> {
                `in`.nextInt().toString()
            }
            JsonToken.BOOLEAN -> {
                `in`.nextBoolean().toString()
            }
            JsonToken.NULL -> {
                `in`.nextNull()
                null
            }
            JsonToken.END_DOCUMENT -> {
                ""
            }
            else -> {
                ""
            }
        }
    }

    override fun write(out: JsonWriter?, value: String?) {
        if (value == null) {
            out?.nullValue()
            return
        }
        out?.value(value)
    }
}