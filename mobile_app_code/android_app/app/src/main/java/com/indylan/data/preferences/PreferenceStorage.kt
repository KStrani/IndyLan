package com.indylan.data.preferences

import com.indylan.data.model.User

/**
 * Storage for app and user preferences.
 */
interface PreferenceStorage {
    var token: String?
    var user: User?
    var nightMode: Int
}