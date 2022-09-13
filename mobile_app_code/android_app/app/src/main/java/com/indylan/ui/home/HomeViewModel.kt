package com.indylan.ui.home

import android.app.Application
import android.net.Uri
import androidx.core.net.toFile
import androidx.lifecycle.MutableLiveData
import com.google.gson.Gson
import com.google.gson.reflect.TypeToken
import com.indylan.BuildConfig
import com.indylan.common.extensions.getMimeType
import com.indylan.data.ApiRepository
import com.indylan.data.model.*
import com.indylan.data.model.base.AppResponse
import com.indylan.data.model.result.Event
import com.indylan.data.model.result.Result
import com.indylan.data.model.result.succeeded
import com.indylan.data.preferences.PreferenceStorage
import com.indylan.ui.base.BaseViewModel
import dagger.hilt.android.lifecycle.HiltViewModel
import kotlinx.coroutines.launch
import okhttp3.MediaType.Companion.toMediaType
import okhttp3.MultipartBody
import okhttp3.RequestBody.Companion.asRequestBody
import okhttp3.RequestBody.Companion.toRequestBody
import org.json.JSONObject
import javax.inject.Inject

@HiltViewModel
class HomeViewModel @Inject constructor(
    application: Application,
    preferenceStorage: PreferenceStorage,
    private val apiRepository: ApiRepository,
    private val gson: Gson
) : BaseViewModel(preferenceStorage, application) {

    val userInfoLiveData = MutableLiveData<Event<User>>()
    val profileImageToolbarLiveData = MutableLiveData<Event<String>>()

    val supportLanguageLiveData = MutableLiveData<Event<List<SupportLanguage>>>()
    val emptySupportLanguageLiveData = MutableLiveData<Event<String>>()

    val menuLanguageLiveData = MutableLiveData<Event<List<Language>>>()
    val emptyMenuLanguageLiveData = MutableLiveData<Event<String>>()

    val targetLanguageLiveData = MutableLiveData<Event<List<Language>>>()
    val emptyTargetLanguageLiveData = MutableLiveData<Event<String>>()

    val exerciseModesLiveData = MutableLiveData<Event<List<ExerciseMode>>>()
    val emptyExerciseModesLiveData = MutableLiveData<Event<String>>()

    val categoriesLiveData = MutableLiveData<Event<List<Category>>>()
    val emptyCategoriesLiveData = MutableLiveData<Event<String>>()

    val subcategoriesLiveData = MutableLiveData<Event<List<Subcategory>>>()
    val emptySubcategoriesLiveData = MutableLiveData<Event<String>>()

    val exerciseTypesLiveData = MutableLiveData<Event<List<ExerciseType>>>()
    val emptyExerciseTypesLiveData = MutableLiveData<Event<String>>()

    val exerciseLiveData = MutableLiveData<Event<String>>()
    val emptyExerciseLiveData = MutableLiveData<Event<String>>()

    fun getUser() {
        scope.launch {
            preferenceStorage.user?.let {
                userInfoLiveData.postValue(Event(it))
                val profileImage = if (!it.social_pic.isNullOrEmpty()) {
                    it.social_pic
                } else if (!it.profile_pic.isNullOrEmpty()) {
                    it.profile_pic
                } else {
                    null
                }
                profileImage?.let { image ->
                    profileImageToolbarLiveData.postValue(Event(image))
                }
            }
            showLoader()
            val userInfo = apiRepository.getUserInfo()
            hideLoader()
            hideLoaderDialog()
            if (userInfo is Result.Success) {
                if (userInfo.succeeded && userInfo.data.status == 1 && userInfo.data.result != null) {
                    preferenceStorage.user = userInfo.data.result
                    userInfoLiveData.postValue(Event(userInfo.data.result))
                    userInfo.data.result.let {
                        val profileImage = if (!it.social_pic.isNullOrEmpty()) {
                            it.social_pic
                        } else if (!it.profile_pic.isNullOrEmpty()) {
                            it.profile_pic
                        } else {
                            null
                        }
                        profileImage?.let { image ->
                            profileImageToolbarLiveData.postValue(Event(image))
                        }
                    }
                } else {
                    showSnackBar(userInfo.data.message ?: "No data found")
                }
            } else if (userInfo is Result.Error) {
                showSnackBar(userInfo.error.message.toString())
            }
        }
    }

    fun editProfile(
        name: String,
        email: String,
        currentPassword: String?,
        newPassword: String?,
        confirmNewPassword: String?,
        isRemovePic: String?,
        image: Uri?
    ) {
        scope.launch {
            preferenceStorage.user?.let {
                showLoaderDialog()
                val imageBody = image?.let {
                    val file = it.toFile()
                    val type = file.path.getMimeType()
                    val mediaType = type.toMediaType()
                    MultipartBody.Part.createFormData(
                        "profile_pic",
                        file.name,
                        file.asRequestBody(mediaType)
                    )
                }
                val textMediaType = "multipart/form-data".toMediaType()
                val parameters = hashMapOf(
                    "user_id" to it.user_id.toString().toRequestBody(textMediaType),
                    "first_name" to name.toRequestBody(textMediaType),
                    "last_name" to "".toRequestBody(textMediaType),
                    "email" to email.toRequestBody(textMediaType),
                    "os_type" to BuildConfig.DEVICE_TYPE.toRequestBody(textMediaType),
                    "social_type" to it.social_type.toString().toRequestBody(textMediaType)
                )
                isRemovePic?.let {
                    parameters["is_remove_pic"] = it.toRequestBody(textMediaType)
                }
                currentPassword?.let {
                    parameters["current_password"] = it.toRequestBody(textMediaType)
                }
                newPassword?.let {
                    parameters["new_password"] = it.toRequestBody(textMediaType)
                }
                confirmNewPassword?.let {
                    parameters["con_new_password"] = it.toRequestBody(textMediaType)
                }
                val edit = apiRepository.editProfile(imageBody, parameters)
                if (edit is Result.Success) {
                    if (edit.succeeded && edit.data.status == 1 && edit.data.result != null) {
                        showSnackBar(edit.data.message)
                        getUser()
                    } else {
                        hideLoaderDialog()
                        showSnackBar(edit.data.message ?: "No data found")
                    }
                } else if (edit is Result.Error) {
                    hideLoaderDialog()
                    showSnackBar(edit.error.message.toString())
                }
            }
        }
    }

    fun fetchSupportLanguages() {
        scope.launch {
            showLoader()
            val languages = apiRepository.supportLanguages()
            hideLoader()
            if (languages is Result.Success) {
                if (languages.succeeded && !languages.data.result.isNullOrEmpty()) {
                    supportLanguageLiveData.postValue(Event(languages.data.result))
                } else {
                    emptySupportLanguageLiveData.postValue(
                        Event(
                            languages.data.message ?: "No data found"
                        )
                    )
                    showMessage(languages.data.message ?: "No data found")
                }
            } else if (languages is Result.Error) {
                emptySupportLanguageLiveData.postValue(Event(languages.error.message.toString()))
                showMessage(languages.error.message.toString())
            }
        }
    }

    fun fetchMenuLanguages() {
        scope.launch {
            showLoader()
            val languages = apiRepository.getSourceLanguage()
            hideLoader()
            if (languages is Result.Success) {
                if (languages.succeeded && !languages.data.result.isNullOrEmpty()) {
                    menuLanguageLiveData.postValue(Event(languages.data.result))
                } else {
                    emptyMenuLanguageLiveData.postValue(
                        Event(
                            languages.data.message ?: "No data found"
                        )
                    )
                    showMessage(languages.data.message ?: "No data found")
                }
            } else if (languages is Result.Error) {
                emptyMenuLanguageLiveData.postValue(Event(languages.error.message.toString()))
                showMessage(languages.error.message.toString())
            }
        }
    }

    fun fetchTargetLanguages() {
        scope.launch {
            showLoader()
            val languages = apiRepository.getSourceLanguage()
            hideLoader()
            if (languages is Result.Success) {
                if (languages.succeeded && !languages.data.result.isNullOrEmpty()) {
                    targetLanguageLiveData.postValue(Event(languages.data.result))
                } else {
                    emptyTargetLanguageLiveData.postValue(
                        Event(
                            languages.data.message ?: "No data found"
                        )
                    )
                    showMessage(languages.data.message ?: "No data found")
                }
            } else if (languages is Result.Error) {
                emptyTargetLanguageLiveData.postValue(Event(languages.error.message.toString()))
                showMessage(languages.error.message.toString())
            }
        }
    }

    fun fetchExerciseModes(supportLanguage: SupportLanguage) {
        scope.launch {
            showLoader()
            val exerciseModes = apiRepository.getExerciseMode(
                hashMapOf("support_lang_id" to supportLanguage.id.toString())
            )
            hideLoader()
            if (exerciseModes is Result.Success) {
                if (exerciseModes.succeeded && !exerciseModes.data.result.isNullOrEmpty()) {
                    exerciseModesLiveData.postValue(Event(exerciseModes.data.result))
                } else {
                    emptyExerciseModesLiveData.postValue(
                        Event(
                            exerciseModes.data.message ?: "No data found"
                        )
                    )
                    showMessage(exerciseModes.data.message ?: "No data found")
                }
            } else if (exerciseModes is Result.Error) {
                emptyExerciseModesLiveData.postValue(Event(exerciseModes.error.message.toString()))
                showMessage(exerciseModes.error.message.toString())
            }
        }
    }

    fun fetchCategories(
        supportLanguage: SupportLanguage,
        language: Language,
        exerciseMode: ExerciseMode
    ) {
        scope.launch {
            showLoader()
            val categories = apiRepository.getCategoryList(
                hashMapOf(
                    "support_lang_id" to supportLanguage.id.toString(),
                    "lang" to language.id.toString(),
                    "exercise_mode_id" to exerciseMode.id.toString()
                )
            )
            hideLoader()
            if (categories is Result.Success) {
                if (categories.succeeded && categories.data.status == 1 && !categories.data.result.isNullOrEmpty()) {
                    categoriesLiveData.postValue(Event(categories.data.result))
                } else {
                    emptyCategoriesLiveData.postValue(
                        Event(
                            categories.data.message ?: "No data found"
                        )
                    )
                    showMessage(categories.data.message ?: "No data found")
                }
            } else if (categories is Result.Error) {
                emptyCategoriesLiveData.postValue(Event(categories.error.message.toString()))
                showMessage(categories.error.message.toString())
            }
        }
    }

    fun fetchSubCategories(
        supportLanguage: SupportLanguage,
        language: Language,
        category: Category
    ) {
        scope.launch {
            showLoader()
            val subcategories = apiRepository.getSubcategoryList(
                hashMapOf(
                    "support_lang_id" to supportLanguage.id.toString(),
                    "lang" to language.id.toString(),
                    "category_id" to category.id.toString(),
                    "user_id" to preferenceStorage.user?.user_id.toString()
                )
            )
            hideLoader()
            if (subcategories is Result.Success) {
                if (subcategories.succeeded && subcategories.data.status == 1 && !subcategories.data.result.isNullOrEmpty()) {
                    subcategoriesLiveData.postValue(Event(subcategories.data.result))
                } else {
                    emptySubcategoriesLiveData.postValue(
                        Event(
                            subcategories.data.message ?: "No data found"
                        )
                    )
                    showMessage(subcategories.data.message ?: "No data found")
                }
            } else if (subcategories is Result.Error) {
                emptySubcategoriesLiveData.postValue(Event(subcategories.error.message.toString()))
                showMessage(subcategories.error.message.toString())
            }
        }
    }

    fun fetchExerciseTypes(
        supportLanguage: SupportLanguage,
        language: Language,
        subcategory: Subcategory
    ) {
        scope.launch {
            showLoader()
            val exerciseTypes = apiRepository.getExerciseTypes(
                hashMapOf(
                    "support_lang_id" to supportLanguage.id.toString(),
                    "lang" to language.id.toString(),
                    "subcategory_id" to subcategory.id.toString()
                )
            )
            hideLoader()
            if (exerciseTypes is Result.Success) {
                if (exerciseTypes.succeeded && exerciseTypes.data.status == 1 && !exerciseTypes.data.result.isNullOrEmpty()) {
                    exerciseTypesLiveData.postValue(Event(exerciseTypes.data.result))
                } else {
                    emptyExerciseTypesLiveData.postValue(
                        Event(
                            exerciseTypes.data.message ?: "No data found"
                        )
                    )
                    showMessage(exerciseTypes.data.message ?: "No data found")
                }
            } else if (exerciseTypes is Result.Error) {
                emptyExerciseTypesLiveData.postValue(Event(exerciseTypes.error.message.toString()))
                showMessage(exerciseTypes.error.message.toString())
            }
        }
    }

    fun fetchExercise(
        supportLanguage: SupportLanguage,
        targetLanguage: Language,
        exerciseMode: ExerciseMode,
        category: Category,
        subcategory: Subcategory,
        exerciseType: ExerciseType
    ) {
        scope.launch {
            showLoader()
            val exercise = apiRepository.getExercise(
                exerciseMode.parseExerciseMode(),
                hashMapOf(
                    "support_lang_id" to supportLanguage.id.toString(),
                    "lang" to targetLanguage.id.toString(),
                    "target_lang" to targetLanguage.id.toString(),
                    "exercise_mode_id" to exerciseMode.id.toString(),
                    "category_id" to category.id.toString(),
                    "subcategory_id" to subcategory.id.toString(),
                    "type" to exerciseType.id.toString()
                )
            )
            hideLoader()
            if (exercise is Result.Success && exercise.succeeded) {
                val response = parseResponse(exercise.data)
                if (response.status == 1 && !response.result.isNullOrEmpty() && response.result != "[]") {
                    exerciseLiveData.postValue(Event(response.result))
                } else {
                    emptyExerciseLiveData.postValue(
                        Event(
                            response.message ?: "No data found"
                        )
                    )
                    showMessage(response.message ?: "No data found")
                }
            } else if (exercise is Result.Error) {
                emptyExerciseLiveData.postValue(Event(exercise.error.message.toString()))
                showMessage(exercise.error.message.toString())
            }
        }
    }

    fun submitScore(
        supportLanguage: SupportLanguage,
        targetLanguage: Language,
        exerciseMode: ExerciseMode,
        category: Category,
        subcategory: Subcategory,
        exerciseType: ExerciseType,
        totalScore: Int,
        myScore: Int
    ) {
        scope.launch {
            val userScore = apiRepository.submitUserScore(
                hashMapOf(
                    "support_lang_id" to supportLanguage.id.toString(),
                    "user_id" to preferenceStorage.user?.user_id.toString(),
                    "lang" to targetLanguage.id.toString(),
                    "target_lang" to targetLanguage.id.toString(),
                    "exercise_mode_id" to exerciseMode.id.toString(),
                    "category_id" to category.id.toString(),
                    "subcategory_id" to subcategory.id.toString(),
                    "type_id" to exerciseType.id.toString(),
                    "total_score" to totalScore.toString(),
                    "correct_score" to myScore.toString()
                )
            )
            if (userScore is Result.Success && userScore.succeeded) {
                //showMessage(userScore.data.message ?: "No data found")
            } else if (userScore is Result.Error) {
                //showMessage(userScore.error.message.toString())
            }
        }
    }

    fun fetchTestExerciseTypes(
        supportLanguage: SupportLanguage,
        targetLanguage: Language,
        exerciseMode: ExerciseMode
    ) {
        scope.launch {
            showLoaderDialog()
            val exerciseTypes = apiRepository.testExerciseType(
                hashMapOf(
                    "support_lang_id" to supportLanguage.id.toString(),
                    "lang" to targetLanguage.id.toString(),
                    "target_lang" to targetLanguage.id.toString(),
                    "exercise_mode_id" to exerciseMode.id.toString()
                )
            )
            hideLoaderDialog()
            if (exerciseTypes is Result.Success) {
                if (exerciseTypes.succeeded && exerciseTypes.data.status == 1 && !exerciseTypes.data.result.isNullOrEmpty()) {
                    exerciseTypesLiveData.postValue(Event(exerciseTypes.data.result))
                } else {
                    emptyExerciseTypesLiveData.postValue(
                        Event(
                            exerciseTypes.data.message ?: "No data found"
                        )
                    )
                    showMessage(exerciseTypes.data.message ?: "No data found")
                }
            } else if (exerciseTypes is Result.Error) {
                emptyExerciseTypesLiveData.postValue(Event(exerciseTypes.error.message.toString()))
                showMessage(exerciseTypes.error.message.toString())
            }
        }
    }

    fun fetchTestExercise(
        supportLanguage: SupportLanguage,
        targetLanguage: Language,
        exerciseMode: ExerciseMode,
        exerciseType: ExerciseType,
        questions: Int
    ) {
        scope.launch {
            showLoader()
            val exercise = apiRepository.testExerciseSection(
                hashMapOf(
                    "support_lang_id" to supportLanguage.id.toString(),
                    "lang" to targetLanguage.id.toString(),
                    "target_lang" to targetLanguage.id.toString(),
                    "exercise_mode_id" to exerciseMode.id.toString(),
                    "type" to exerciseType.id.toString(),
                    "question" to questions.toString()
                )
            )
            hideLoader()
            if (exercise is Result.Success && exercise.succeeded) {
                val response = parseResponse(exercise.data)
                if (response.status == 1 && !response.result.isNullOrEmpty() && response.result != "[]") {
                    exerciseLiveData.postValue(Event(response.result))
                } else {
                    emptyExerciseLiveData.postValue(
                        Event(
                            response.message ?: "No data found"
                        )
                    )
                    showMessage(response.message ?: "No data found")
                }
            } else if (exercise is Result.Error) {
                emptyExerciseLiveData.postValue(Event(exercise.error.message.toString()))
                showMessage(exercise.error.message.toString())
            }
        }
    }

    private fun parseResponse(response: String): AppResponse<String> {
        val obj = JSONObject(response)
        return AppResponse(
            obj.optInt("status", 0),
            obj.optString("message", ""),
            obj.optJSONArray("result")?.toString()
        )
    }

    fun getExerciseModesFromFile(): List<ExerciseMode> {
        val response = apiRepository.getExerciseModesFromFile()
        return if (response is Result.Success) {
            response.data.result.orEmpty()
        } else {
            emptyList()
        }
    }

    fun parseTranslationExercise(response: String): List<ExerciseTranslation> {
        return gson.fromJson(response, object : TypeToken<List<ExerciseTranslation>>() {}.type)
    }

    fun parseMultipleChoiceImageExercise(response: String): List<ExerciseTranslation> {
        return gson.fromJson(response, object : TypeToken<List<ExerciseTranslation>>() {}.type)
    }

    fun parsePictureAnswerExercises(response: String): List<ExercisePictureAnswer> {
        return gson.fromJson(response, object : TypeToken<List<ExercisePictureAnswer>>() {}.type)
    }

    fun parseMatchAnswerExercises(response: String): List<ExerciseMatchAnswer> {
        return gson.fromJson(response, object : TypeToken<List<ExerciseMatchAnswer>>() {}.type)
    }

    fun parseChooseLettersExercise(response: String): List<ExerciseSingleAnswer> {
        return gson.fromJson(response, object : TypeToken<List<ExerciseSingleAnswer>>() {}.type)
    }

    fun parseWriteWordExercise(response: String): List<ExerciseSingleAnswer> {
        return gson.fromJson(response, object : TypeToken<List<ExerciseSingleAnswer>>() {}.type)
    }

    fun parseFlashCardExercise(response: String): List<ExerciseSingleAnswer> {
        return gson.fromJson(response, object : TypeToken<List<ExerciseSingleAnswer>>() {}.type)
    }

    fun parseListeningExercise(response: String): List<ExerciseSingleAnswer> {
        return gson.fromJson(response, object : TypeToken<List<ExerciseSingleAnswer>>() {}.type)
    }

    fun parseDialogExercise(response: String): List<ExerciseDialog> {
        return gson.fromJson(response, object : TypeToken<List<ExerciseDialog>>() {}.type)
    }

    fun parseFillGapExercise(response: String): List<ExerciseFillGap> {
        return gson.fromJson(response, object : TypeToken<List<ExerciseFillGap>>() {}.type)
    }

    fun parseTextComprehensionExercises(response: String): List<ExerciseTextComprehension> {
        return gson.fromJson(
            response,
            object : TypeToken<List<ExerciseTextComprehension>>() {}.type
        )
    }

    fun parseTextComprehensionExercise(response: String): ExerciseTextComprehension {
        return gson.fromJson(response, object : TypeToken<ExerciseTextComprehension>() {}.type)
    }
}