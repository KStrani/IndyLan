package com.indylan.data

import android.app.Application
import com.google.gson.Gson
import com.google.gson.reflect.TypeToken
import com.indylan.R
import com.indylan.data.model.base.AppResponse
import com.indylan.data.model.result.Result
import retrofit2.Response
import java.lang.reflect.Type
import java.net.UnknownHostException
import java.util.*

open class BaseRepository constructor(val application: Application, val gson: Gson) {

    suspend fun <T : Any> safeApiCall(
        type: Type?,
        call: suspend () -> Response<String>
    ): Result<T> {
        return safeApiResult(type, call)
    }

    suspend fun safeApiCall(call: suspend () -> Response<String>): Result<String> {
        return safeApiResult(call)
    }

    private suspend fun <T : Any> safeApiResult(
        type: Type?,
        call: suspend () -> Response<String>
    ): Result<T> {
        return try {
            val response = call.invoke()
            when {
                response.isSuccessful -> {
                    val resp = response.body().toString()
                    val objAny = gson.fromJson<AppResponse<Any>>(
                        resp,
                        object : TypeToken<AppResponse<Any>>() {}.type
                    )
                    if (objAny.status == 1) {
                        if (type != null) {
                            val obj = gson.fromJson<T>(resp, type)
                            Result.Success(obj)
                        } else {
                            val obj = gson.fromJson<T>(
                                resp,
                                object : TypeToken<AppResponse<String>>() {}.type
                            )
                            Result.Success(obj)
                        }
                    } else {
                        Result.Error(objAny)
                    }
                }
                else -> Result.Error(
                    AppResponse(
                        response.code(),
                        getErrorMessage(response.code())
                    )
                )
            }
        } catch (e: Exception) {
            e.printStackTrace()
            if (e is UnknownHostException) {
                Result.Error(
                    AppResponse(
                        0,
                        application.getString(R.string.no_internet_connection)
                    )
                )
            } else {
                Result.Error(
                    AppResponse(
                        0,
                        getErrorMessage(0)
                    )
                )
            }
        }
    }

    private suspend fun safeApiResult(call: suspend () -> Response<String>): Result<String> {
        return try {
            val response = call.invoke()
            when {
                response.isSuccessful -> {
                    val resp = response.body().toString()
                    val objAny = gson.fromJson<AppResponse<Any>>(
                        resp,
                        object : TypeToken<AppResponse<Any>>() {}.type
                    )
                    if (objAny.status == 1) {
                        Result.Success(resp)
                    } else {
                        Result.Error(objAny)
                    }
                }
                else -> Result.Error(
                    AppResponse(
                        response.code(),
                        getErrorMessage(response.code())
                    )
                )
            }
        } catch (e: Exception) {
            e.printStackTrace()
            if (e is UnknownHostException) {
                Result.Error(
                    AppResponse(
                        0,
                        application.getString(R.string.no_internet_connection)
                    )
                )
            } else {
                Result.Error(
                    AppResponse(
                        0,
                        getErrorMessage(0)
                    )
                )
            }
        }
    }

    private fun getErrorMessage(code: Int): String {
        return when (code) {
            502 -> "Bad Gateway"
            504 -> "Unable to connect to server"
            else -> application.getString(R.string.something_went_wrong)
        }
    }

    fun buildString(parameters: HashMap<String, String>): String {
        return parameters.asSequence().map {
            it.key + "=" + it.value
        }.joinToString(separator = "&")
    }
}