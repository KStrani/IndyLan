package com.indylan.data.api

import okhttp3.MultipartBody
import okhttp3.RequestBody
import retrofit2.Response
import retrofit2.http.*

@JvmSuppressWildcards
interface ApiService {

    @FormUrlEncoded
    @POST("login")
    suspend fun login(@FieldMap parameters: Map<String, String>): Response<String>

    @FormUrlEncoded
    @POST("forgot_password")
    suspend fun forgotPassword(@FieldMap parameters: Map<String, String>): Response<String>

    @Multipart
    @POST("register")
    suspend fun register(
        @Part file: MultipartBody.Part?,
        @PartMap parameters: Map<String, RequestBody>
    ): Response<String>

    @Multipart
    @POST("edit_profile")
    suspend fun editProfile(
        @Part file: MultipartBody.Part?,
        @PartMap parameters: Map<String, RequestBody>
    ): Response<String>

    @FormUrlEncoded
    @POST("get_user_info")
    suspend fun getUserInfo(@FieldMap parameters: Map<String, String>): Response<String>

    @FormUrlEncoded
    @POST("submit_user_score")
    suspend fun submitUserScore(@FieldMap parameters: Map<String, String>): Response<String>

    @GET("get_support_language")
    suspend fun getSupportLanguage(): Response<String>

    @GET("get_source_language")
    suspend fun getSourceLanguage(): Response<String>

    @FormUrlEncoded
    @POST("get_exercise_mode")
    suspend fun getExerciseMode(@FieldMap parameters: Map<String, String>): Response<String>

    @FormUrlEncoded
    @POST("get_category_list")
    suspend fun getCategoryList(@FieldMap parameters: Map<String, String>): Response<String>

    @FormUrlEncoded
    @POST("get_subcategory_list")
    suspend fun getSubcategoryList(@FieldMap parameters: Map<String, String>): Response<String>

    @FormUrlEncoded
    @POST("get_exercise_type")
    suspend fun getExerciseTypes(@FieldMap parameters: Map<String, String>): Response<String>

    @FormUrlEncoded
    @POST("{exercise_mode}")
    suspend fun getExercise(
        @Path("exercise_mode") exerciseMode: String,
        @FieldMap parameters: Map<String, String>
    ): Response<String>

    @FormUrlEncoded
    @POST("test_exercise_type")
    suspend fun testExerciseType(@FieldMap parameters: Map<String, String>): Response<String>

    @FormUrlEncoded
    @POST("test_exercise_section")
    suspend fun testExerciseSection(@FieldMap parameters: Map<String, String>): Response<String>
}