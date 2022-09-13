package com.indylan.ui.auth

import android.app.Application
import androidx.lifecycle.MutableLiveData
import com.indylan.BuildConfig
import com.indylan.common.extensions.getMimeType
import com.indylan.data.ApiRepository
import com.indylan.data.model.LoginType
import com.indylan.data.model.result.Event
import com.indylan.data.model.result.Result
import com.indylan.data.model.result.succeeded
import com.indylan.data.preferences.PreferenceStorage
import com.indylan.ui.base.BaseViewModel
import dagger.hilt.android.lifecycle.HiltViewModel
import kotlinx.coroutines.delay
import kotlinx.coroutines.launch
import okhttp3.MediaType.Companion.toMediaType
import okhttp3.MultipartBody
import okhttp3.RequestBody.Companion.asRequestBody
import okhttp3.RequestBody.Companion.toRequestBody
import java.io.File
import javax.inject.Inject

@HiltViewModel
class AuthenticationViewModel @Inject constructor(
    application: Application,
    private val apiRepository: ApiRepository,
    preferenceStorage: PreferenceStorage
) : BaseViewModel(preferenceStorage, application) {

    val loginScreenLiveData = MutableLiveData<Event<Unit>>()
    val homeScreenLiveData = MutableLiveData<Event<Unit>>()

    fun showNext() {
        scope.launch {
            delay(2000)
            if (preferenceStorage.user == null) {
                loginScreenLiveData.postValue(Event(Unit))
            } else {
                /*if (preferenceStorage.user?.social_type == "") {
                    // Do only if social type is Facebook
                    val accessToken = AccessToken.getCurrentAccessToken()
                    if (accessToken != null && !accessToken.isExpired) {
                        homeScreenLiveData.postValue(Event(Unit))
                    } else {
                        loginScreenLiveData.postValue(Event(Unit))
                    }
                } else {
                    homeScreenLiveData.postValue(Event(Unit))
                }*/
                homeScreenLiveData.postValue(Event(Unit))
            }
        }
    }

    fun login(email: String, password: String) {
        scope.launch {
            showLoaderDialog()
            val login = apiRepository.login(hashMapOf("email" to email, "password" to password))
            hideLoaderDialog()
            if (login is Result.Success) {
                if (login.succeeded && login.data.status == 1 && login.data.result != null) {
                    preferenceStorage.user = login.data.result
                    homeScreenLiveData.postValue(Event(Unit))
                } else {
                    showSnackBar(login.data.message ?: "No data found")
                }
            } else if (login is Result.Error) {
                showSnackBar(login.error.message.toString())
            }
        }
    }

    fun loginGoogle(name: String, email: String, profile: String, id: String) {
        scope.launch {
            showLoaderDialog()
            val textMediaType = "multipart/form-data".toMediaType()
            val login = apiRepository.register(
                null, hashMapOf(
                    "first_name" to name.toRequestBody(textMediaType),
                    "last_name" to "".toRequestBody(textMediaType),
                    "password" to "".toRequestBody(textMediaType),
                    "confirm_password" to "".toRequestBody(textMediaType),
                    "os_type" to BuildConfig.DEVICE_TYPE.toRequestBody(textMediaType),
                    "email" to email.toRequestBody(textMediaType),
                    "profile_pic" to profile.toRequestBody(textMediaType),
                    "social_id" to id.toRequestBody(textMediaType),
                    "social_type" to LoginType.GOOGLE.value.toRequestBody(textMediaType)
                )
            )
            hideLoaderDialog()
            if (login is Result.Success) {
                if (login.succeeded && login.data.status == 1 && login.data.result != null) {
                    preferenceStorage.user = login.data.result
                    homeScreenLiveData.postValue(Event(Unit))
                } else {
                    showSnackBar(login.data.message ?: "No data found")
                }
            } else if (login is Result.Error) {
                showSnackBar(login.error.message.toString())
            }
        }
    }

    fun loginFacebook(
        firstName: String,
        lastName: String,
        email: String,
        profile: String,
        id: String
    ) {
        scope.launch {
            showLoaderDialog()
            val textMediaType = "multipart/form-data".toMediaType()
            val login = apiRepository.register(
                null, hashMapOf(
                    "first_name" to firstName.toRequestBody(textMediaType),
                    "last_name" to lastName.toRequestBody(textMediaType),
                    "password" to "".toRequestBody(textMediaType),
                    "confirm_password" to "".toRequestBody(textMediaType),
                    "os_type" to BuildConfig.DEVICE_TYPE.toRequestBody(textMediaType),
                    "email" to email.toRequestBody(textMediaType),
                    "profile_pic" to profile.toRequestBody(textMediaType),
                    "social_id" to id.toRequestBody(textMediaType),
                    "social_type" to LoginType.FACEBOOK.value.toRequestBody(textMediaType)
                )
            )
            hideLoaderDialog()
            if (login is Result.Success) {
                if (login.succeeded && login.data.status == 1 && login.data.result != null) {
                    preferenceStorage.user = login.data.result
                    homeScreenLiveData.postValue(Event(Unit))
                } else {
                    showSnackBar(login.data.message ?: "No data found")
                }
            } else if (login is Result.Error) {
                showSnackBar(login.error.message.toString())
            }
        }
    }

    fun forgotPassword(email: String) {
        scope.launch {
            showLoaderDialog()
            val forgotPassword = apiRepository.forgotPassword(hashMapOf("email" to email))
            hideLoaderDialog()
            if (forgotPassword is Result.Success) {
                if (forgotPassword.succeeded && forgotPassword.data.status == 1) {
                    showSnackBar(forgotPassword.data.message)
                    goBack()
                } else {
                    showSnackBar(forgotPassword.data.message ?: "No data found")
                }
            } else if (forgotPassword is Result.Error) {
                showSnackBar(forgotPassword.error.message.toString())
            }
        }
    }

    fun register(
        name: String,
        email: String,
        password: String,
        loginType: LoginType,
        image: String?
    ) {
        scope.launch {
            showLoaderDialog()
            val imageBody = image?.let {
                val file = File(image)
                val type = it.getMimeType()
                val mediaType = type.toMediaType()
                MultipartBody.Part.createFormData(
                    "profile_pic",
                    file.name,
                    file.asRequestBody(mediaType)
                )
            }
            val textMediaType = "multipart/form-data".toMediaType()
            val register = apiRepository.register(
                imageBody,
                hashMapOf(
                    "first_name" to name.toRequestBody(textMediaType),
                    "last_name" to "".toRequestBody(textMediaType),
                    "email" to email.toRequestBody(textMediaType),
                    "password" to password.toRequestBody(textMediaType),
                    "confirm_password" to password.toRequestBody(textMediaType),
                    "os_type" to BuildConfig.DEVICE_TYPE.toRequestBody(textMediaType),
                    "social_type" to loginType.value.toRequestBody(textMediaType)
                )
            )
            hideLoaderDialog()
            if (register is Result.Success) {
                if (register.succeeded && register.data.status == 1 && register.data.result != null) {
                    preferenceStorage.user = register.data.result
                    homeScreenLiveData.postValue(Event(Unit))
                } else {
                    showSnackBar(register.data.message ?: "No data found")
                }
            } else if (register is Result.Error) {
                showSnackBar(register.error.message.toString())
            }
        }
    }
}