package com.indylan.data.model.base

import com.google.gson.annotations.Expose
import com.google.gson.annotations.SerializedName

data class AppResponse<T>(
    @SerializedName("status")
    @Expose val status: Int = 0,
    @SerializedName("message")
    @Expose val message: String? = null,
    @SerializedName("result")
    @Expose val result: T? = null
)