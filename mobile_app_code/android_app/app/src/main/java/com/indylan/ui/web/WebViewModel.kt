package com.indylan.ui.web

import android.app.Application
import com.indylan.data.ApiRepository
import com.indylan.data.preferences.PreferenceStorage
import com.indylan.ui.base.BaseViewModel
import dagger.hilt.android.lifecycle.HiltViewModel
import javax.inject.Inject

@HiltViewModel
class WebViewModel @Inject constructor(
    application: Application,
    preferenceStorage: PreferenceStorage,
    private val apiRepository: ApiRepository
) : BaseViewModel(preferenceStorage, application) {

}