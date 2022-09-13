package com.indylan.ui.auth

import android.os.Bundle
import android.view.View
import androidx.navigation.NavController
import androidx.navigation.NavDestination
import androidx.navigation.fragment.NavHostFragment
import com.indylan.R
import com.indylan.common.theme.ThemePreferencesManager
import com.indylan.databinding.ActivityAuthenticationBinding
import com.indylan.databinding.LayoutToolbarBinding
import com.indylan.ui.base.BaseActivity
import com.yariksoffice.lingver.Lingver
import dagger.hilt.android.AndroidEntryPoint
import javax.inject.Inject

@AndroidEntryPoint
class AuthenticationActivity : BaseActivity(), NavController.OnDestinationChangedListener {

    @Inject
    lateinit var themePreferencesManager: ThemePreferencesManager

    private val binding by lazy {
        ActivityAuthenticationBinding.inflate(layoutInflater)
    }
    private val navController by lazy {
        val navHostFragment =
            supportFragmentManager.findFragmentById(R.id.navHostFragmentAuth) as? NavHostFragment
        navHostFragment?.navController
    }

    override fun findContentView(): View? = binding.root

    override fun toolbar(): LayoutToolbarBinding? = binding.includeToolbar

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        themePreferencesManager.applyTheme()
        Lingver.getInstance().setLocale(this, "en")
    }

    override fun onStart() {
        super.onStart()
        navController?.addOnDestinationChangedListener(this)
    }

    override fun onStop() {
        navController?.removeOnDestinationChangedListener(this)
        super.onStop()
    }

    override fun onDestinationChanged(
        controller: NavController,
        destination: NavDestination,
        arguments: Bundle?
    ) {
        if (destination.id == R.id.splashFragment || destination.id == R.id.loginFragment) {
            setAuthView()
        } else {
            setHomeView()
        }
    }
}