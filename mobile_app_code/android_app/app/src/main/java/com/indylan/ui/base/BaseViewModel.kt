package com.indylan.ui.base

import android.app.Application
import androidx.lifecycle.AndroidViewModel
import androidx.lifecycle.MutableLiveData
import androidx.lifecycle.viewModelScope
import com.indylan.data.model.result.Event
import com.indylan.data.preferences.PreferenceStorage
import javax.inject.Inject

open class BaseViewModel @Inject constructor(
    val preferenceStorage: PreferenceStorage,
    application: Application
) : AndroidViewModel(application) {

    val scope = viewModelScope

    val messageLiveData = MutableLiveData<Event<String?>>()
    val snackBarLiveData = MutableLiveData<Event<String?>>()
    val showLoadingDialogLiveData = MutableLiveData<Event<Unit>>()
    val hideLoadingDialogLiveData = MutableLiveData<Event<Unit>>()
    val showLoadingLiveData = MutableLiveData<Event<Unit>>()
    val hideLoadingLiveData = MutableLiveData<Event<Unit>>()
    val backLiveData = MutableLiveData<Event<Unit>>()

    fun showMessage(message: String?) {
        messageLiveData.postValue(Event(message))
    }

    fun showSnackBar(message: String?) {
        snackBarLiveData.postValue(Event(message))
    }

    fun showLoader() {
        showLoadingLiveData.postValue(Event(Unit))
    }

    fun hideLoader() {
        hideLoadingLiveData.postValue(Event(Unit))
    }

    fun showLoaderDialog() {
        showLoadingDialogLiveData.postValue(Event(Unit))
    }

    fun hideLoaderDialog() {
        hideLoadingDialogLiveData.postValue(Event(Unit))
    }

    fun goBack() {
        backLiveData.postValue(Event(Unit))
    }

    fun logout() {
        preferenceStorage.user = null
    }
}