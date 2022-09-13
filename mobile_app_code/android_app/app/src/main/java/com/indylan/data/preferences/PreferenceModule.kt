package com.indylan.data.preferences

import dagger.Binds
import dagger.Module
import dagger.hilt.InstallIn
import dagger.hilt.components.SingletonComponent

@Module
@InstallIn(SingletonComponent::class)
abstract class PreferenceModule {

    @Binds
    abstract fun bindSharedPreferenceStorage(sharedPreferenceStorage: SharedPreferenceStorage): PreferenceStorage
}