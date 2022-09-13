package com.indylan.data

import android.app.Application
import com.google.gson.Gson
import com.google.gson.reflect.TypeToken
import com.indylan.BuildConfig
import com.indylan.R
import com.indylan.common.device.DeviceInfoProvider
import com.indylan.data.api.ApiService
import com.indylan.data.model.*
import com.indylan.data.model.base.AppResponse
import com.indylan.data.model.result.Result
import com.indylan.data.preferences.PreferenceStorage
import okhttp3.MultipartBody
import okhttp3.RequestBody
import timber.log.Timber
import javax.inject.Inject
import javax.inject.Singleton

@Singleton
open class ApiRepository @Inject constructor(
    application: Application,
    gson: Gson,
    private val apiService: ApiService,
    private val preferenceStorage: PreferenceStorage,
    private val deviceInfoProvider: DeviceInfoProvider
) : BaseRepository(application, gson) {

    suspend fun login(parameters: Map<String, String>): Result<AppResponse<User>> {
        return if (!BuildConfig.IS_PROTOTYPE) {
            safeApiCall(object : TypeToken<AppResponse<User>>() {}.type) {
                apiService.login(parameters)
            }
        } else {
            Result.Success(
                AppResponse(
                    1, null, User(
                        user_id = "1",
                        first_name = "John",
                        last_name = "Doe",
                        email = "johndoe@email.com",
                        password = "password",
                        os_type = BuildConfig.DEVICE_TYPE,
                        social_type = LoginType.NORMAL.value
                    )
                )
            )
        }
    }

    suspend fun forgotPassword(parameters: Map<String, String>): Result<AppResponse<Any>> {
        return if (!BuildConfig.IS_PROTOTYPE) {
            safeApiCall(object : TypeToken<AppResponse<Any>>() {}.type) {
                apiService.forgotPassword(parameters)
            }
        } else {
            Result.Success(AppResponse(1, "Password sent to your email address"))
        }
    }

    suspend fun register(
        image: MultipartBody.Part?,
        parameters: Map<String, RequestBody>
    ): Result<AppResponse<User>> {
        return if (!BuildConfig.IS_PROTOTYPE) {
            safeApiCall(object : TypeToken<AppResponse<User>>() {}.type) {
                apiService.register(image, parameters)
            }
        } else {
            Result.Success(
                AppResponse(
                    1, "Registered successfully",
                    User(
                        user_id = "1",
                        first_name = "John",
                        last_name = "Doe",
                        email = "johndoe@email.com",
                        password = "password",
                        os_type = BuildConfig.DEVICE_TYPE,
                        social_type = LoginType.NORMAL.value
                    )
                )
            )
        }
    }

    suspend fun editProfile(
        image: MultipartBody.Part?,
        parameters: Map<String, RequestBody>
    ): Result<AppResponse<Any>> {
        return if (!BuildConfig.IS_PROTOTYPE) {
            safeApiCall(object : TypeToken<AppResponse<Any>>() {}.type) {
                apiService.editProfile(
                    image,
                    parameters
                )
            }
        } else {
            Result.Success(
                AppResponse(
                    1, "Profile updated successfully"
                )
            )
        }
    }

    suspend fun getUserInfo(): Result<AppResponse<User>> {
        return if (!BuildConfig.IS_PROTOTYPE) {
            safeApiCall(object : TypeToken<AppResponse<User>>() {}.type) {
                apiService.getUserInfo(
                    hashMapOf("user_id" to preferenceStorage.user?.user_id.toString())
                )
            }
        } else {
            Result.Success(
                AppResponse(
                    1, "User details",
                    User(
                        user_id = "1",
                        first_name = "John",
                        last_name = "Doe",
                        email = "johndoe@email.com",
                        password = "password",
                        os_type = BuildConfig.DEVICE_TYPE,
                        social_type = LoginType.NORMAL.value
                    )
                )
            )
        }
    }

    suspend fun submitUserScore(parameters: Map<String, String>): Result<AppResponse<Any>> {
        return if (!BuildConfig.IS_PROTOTYPE) {
            safeApiCall(object : TypeToken<AppResponse<Any>>() {}.type) {
                apiService.submitUserScore(parameters)
            }
        } else {
            Result.Success(AppResponse(1, "Score submitted"))
        }
    }

    suspend fun getSourceLanguage(): Result<AppResponse<List<Language>>> {
        return if (!BuildConfig.IS_PROTOTYPE) {
            safeApiCall(object : TypeToken<AppResponse<List<Language>>>() {}.type) {
                apiService.getSourceLanguage()
            }
        } else {
            getLanguagesFromFile()
        }
    }

    suspend fun getExerciseMode(parameters: Map<String, String>): Result<AppResponse<List<ExerciseMode>>> {
        return if (!BuildConfig.IS_PROTOTYPE) {
            /*safeApiCall(object : TypeToken<AppResponse<List<ExerciseMode>>>() {}.type) {
                apiService.getExerciseMode(parameters)
            }*/
            getExerciseModesFromFile()
        } else {
            getExerciseModesFromFile()
        }
    }

    suspend fun getCategoryList(parameters: Map<String, String>): Result<AppResponse<List<Category>>> {
        return if (!BuildConfig.IS_PROTOTYPE) {
            safeApiCall(object : TypeToken<AppResponse<List<Category>>>() {}.type) {
                apiService.getCategoryList(parameters)
            }
        } else {
            getCategoriesFromFile()
        }
    }

    suspend fun getSubcategoryList(parameters: Map<String, String>): Result<AppResponse<List<Subcategory>>> {
        return if (!BuildConfig.IS_PROTOTYPE) {
            safeApiCall(object : TypeToken<AppResponse<List<Subcategory>>>() {}.type) {
                apiService.getSubcategoryList(parameters)
            }
        } else {
            getSubCategoriesFromFile()
        }
    }

    suspend fun getExerciseTypes(parameters: Map<String, String>): Result<AppResponse<List<ExerciseType>>> {
        return if (!BuildConfig.IS_PROTOTYPE) {
            safeApiCall(object : TypeToken<AppResponse<List<ExerciseType>>>() {}.type) {
                apiService.getExerciseTypes(parameters)
            }
        } else {
            getExerciseTypesFromFile()
        }
    }

    suspend fun getExercise(
        exerciseMode: String,
        parameters: Map<String, String>
    ): Result<String> {
        return if (!BuildConfig.IS_PROTOTYPE) {
            safeApiCall {
                apiService.getExercise(exerciseMode, parameters)
            }
        } else {
            Result.Success("")
            //getExerciseFromFile()
        }
    }

    suspend fun testExerciseType(parameters: Map<String, String>): Result<AppResponse<List<ExerciseType>>> {
        return if (!BuildConfig.IS_PROTOTYPE) {
            safeApiCall(object : TypeToken<AppResponse<List<ExerciseType>>>() {}.type) {
                apiService.testExerciseType(parameters)
            }
        } else {
            Result.Success(AppResponse(1, "Success"))
        }
    }

    suspend fun testExerciseSection(parameters: Map<String, String>): Result<String> {
        return if (!BuildConfig.IS_PROTOTYPE) {
            safeApiCall {
                apiService.testExerciseSection(parameters)
            }
        } else {
            Result.Success("")
        }
    }

    suspend fun supportLanguages(): Result<AppResponse<List<SupportLanguage>>> {
        return if (!BuildConfig.IS_PROTOTYPE) {
            safeApiCall(object : TypeToken<AppResponse<List<SupportLanguage>>>() {}.type) {
                apiService.getSupportLanguage()
            }
        } else {
            Result.Success(
                AppResponse(
                    1, "Success",
                    listOf(
                        SupportLanguage("8", "Finnish"),
                        SupportLanguage("9", "Swedish")
                    )
                )
            )
        }
    }

    /*
    * Fake Data Methods
    * */

    fun getLanguagesFromFile(): Result<AppResponse<List<Language>>> {

        Timber.d("Reading Languages JSON...")
        val languagesJson = application.resources.openRawResource(R.raw.languages)
            .bufferedReader().use { it.readText() }

        val type = object : TypeToken<AppResponse<List<Language>>>() {}.type

        Timber.d("Parsing Languages JSON...")
        val languages = gson.fromJson<AppResponse<List<Language>>>(languagesJson, type)

        return Result.Success<AppResponse<List<Language>>>(languages)
    }

    fun getExerciseModesFromFile(): Result<AppResponse<List<ExerciseMode>>> {

        Timber.d("Reading Exercise Modes JSON...")
        val exerciseModeJson = application.resources.openRawResource(R.raw.exercise_modes)
            .bufferedReader().use { it.readText() }

        val type = object : TypeToken<AppResponse<List<ExerciseMode>>>() {}.type

        Timber.d("Parsing Exercise Modes JSON...")
        val exerciseModes = gson.fromJson<AppResponse<List<ExerciseMode>>>(exerciseModeJson, type)

        return Result.Success<AppResponse<List<ExerciseMode>>>(exerciseModes)
    }

    fun getCategoriesFromFile(): Result<AppResponse<List<Category>>> {

        Timber.d("Reading Categories JSON...")
        val categoriesJson = application.resources.openRawResource(R.raw.categories)
            .bufferedReader().use { it.readText() }

        val type = object : TypeToken<AppResponse<List<Category>>>() {}.type

        Timber.d("Parsing Categories JSON...")
        val categories = gson.fromJson<AppResponse<List<Category>>>(categoriesJson, type)

        return Result.Success<AppResponse<List<Category>>>(categories)
    }

    fun getSubCategoriesFromFile(): Result<AppResponse<List<Subcategory>>> {

        Timber.d("Reading Subcategories JSON...")
        val categoriesJson = application.resources.openRawResource(R.raw.subcategories)
            .bufferedReader().use { it.readText() }

        val type = object : TypeToken<AppResponse<List<Subcategory>>>() {}.type

        Timber.d("Parsing Subcategories JSON...")
        val categories = gson.fromJson<AppResponse<List<Subcategory>>>(categoriesJson, type)

        return Result.Success<AppResponse<List<Subcategory>>>(categories)
    }

    fun getExerciseTypesFromFile(): Result<AppResponse<List<ExerciseType>>> {

        Timber.d("Reading Exercise Types JSON...")
        val categoriesJson = application.resources.openRawResource(R.raw.exercise_types)
            .bufferedReader().use { it.readText() }

        val type = object : TypeToken<AppResponse<List<ExerciseType>>>() {}.type

        Timber.d("Parsing Exercise Types JSON...")
        val categories = gson.fromJson<AppResponse<List<ExerciseType>>>(categoriesJson, type)

        return Result.Success<AppResponse<List<ExerciseType>>>(categories)
    }

    fun getExerciseFromFile(): Result<AppResponse<List<ExerciseSingleAnswer>>> {

        Timber.d("Reading Exercise JSON...")
        val categoriesJson = application.resources.openRawResource(R.raw.exercise)
            .bufferedReader().use { it.readText() }

        val type = object : TypeToken<AppResponse<List<ExerciseSingleAnswer>>>() {}.type

        Timber.d("Parsing Exercise JSON...")
        val categories =
            gson.fromJson<AppResponse<List<ExerciseSingleAnswer>>>(categoriesJson, type)

        return Result.Success<AppResponse<List<ExerciseSingleAnswer>>>(categories)
    }
}