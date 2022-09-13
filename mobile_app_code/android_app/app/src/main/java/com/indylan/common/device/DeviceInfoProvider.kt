package com.indylan.common.device

import android.content.Context
import android.net.ConnectivityManager
import android.os.Build
import com.indylan.BuildConfig
import com.indylan.data.preferences.PreferenceStorage
import org.json.JSONObject
import java.util.*
import javax.inject.Inject
import javax.inject.Singleton

@Singleton
class DeviceInfoProvider @Inject constructor(
    private val context: Context,
    private val preferenceStorage: PreferenceStorage,
    private val connectivityManager: ConnectivityManager
) {

    fun hasInternetConnection(): Boolean {
        //val activeNetworkInfo = connectivityManager.activeNetworkInfo
        //return activeNetworkInfo != null && activeNetworkInfo.isConnectedOrConnecting
        return true
    }

    fun getAppVersionCode(): String = BuildConfig.VERSION_CODE.toString()

    fun getAppVersionName(): String = BuildConfig.VERSION_NAME

    fun getDeviceOS(): String = BuildConfig.DEVICE_TYPE

    fun getDeviceType(): String = BuildConfig.DEVICE_TYPE

    fun getDeviceOSVersion(): String = Build.VERSION.SDK_INT.toString()

    fun getDeviceModel(): String {
        val manufacturer = Build.MANUFACTURER
        val model = Build.MODEL
        return if (model.startsWith(manufacturer, true)) {
            model.replaceFirstChar { if (it.isLowerCase()) it.titlecase(Locale.getDefault()) else it.toString() }
        } else {
            manufacturer.replaceFirstChar { if (it.isLowerCase()) it.titlecase(Locale.getDefault()) else it.toString() } + " " + model
        }
    }

    fun getDeviceToken(): String = BuildConfig.DEVICE_TYPE

    fun getDeviceIPAddress(): String = "0.0.0.0"

    fun getDeviceMacAddress(): String = "00:00:00:00:00:00"

    fun getDeviceWiFiNetwork(): String = ""

    fun addCommonParameters(parameters: HashMap<String, Any>): HashMap<String, Any> {
        parameters["app_version_code"] = getAppVersionCode()
        parameters["device_os"] = getDeviceOS()
        parameters["device_type"] = getDeviceType()
        parameters["os_version"] = getDeviceOSVersion()
        parameters["device_model"] = getDeviceModel()
        parameters["device_token"] = getDeviceToken()
        return parameters
    }

    fun addConnectionParameters(parameters: HashMap<String, String>): HashMap<String, String> {
        parameters["ip_address"] = getDeviceIPAddress()
        parameters["mac_address"] = getDeviceMacAddress()
        parameters["wifi_network"] = getDeviceWiFiNetwork()
        return parameters
    }

    fun addCommonParametersJson(parameters: HashMap<String, Any>): JSONObject {
        return JSONObject(addCommonParameters(parameters) as Map<String, Any>)
    }

    fun mapToJson(parameters: Map<String, String>): JSONObject {
        return JSONObject(parameters)
    }
}