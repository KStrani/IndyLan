package com.indylan.ui.home

import android.os.Bundle
import android.view.View
import androidx.activity.viewModels
import androidx.navigation.fragment.NavHostFragment
import com.indylan.R
import com.indylan.common.glide.GlideApp
import com.indylan.data.model.result.EventObserver
import com.indylan.databinding.ActivityHomeBinding
import com.indylan.databinding.LayoutToolbarBinding
import com.indylan.ui.base.BaseActivity
import dagger.hilt.android.AndroidEntryPoint

@AndroidEntryPoint
class HomeActivity : BaseActivity() {

    private val homeViewModel: HomeViewModel by viewModels()

    private val binding by lazy {
        ActivityHomeBinding.inflate(layoutInflater)
    }
    private val navController by lazy {
        val navHostFragment =
            supportFragmentManager.findFragmentById(R.id.navHostFragment) as NavHostFragment
        navHostFragment.navController
    }

    override fun findContentView(): View = binding.root

    override fun toolbar(): LayoutToolbarBinding = binding.includeToolbar

    /*val home = arrayOf(R.id.languageSelectionFragment, R.id.homeFragment)
    val quiz = arrayOf(R.id.questionsFragment)*/

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setHomeView()
        /*onBackPressedDispatcher.addCallback(this) {
            when (findNavController(R.id.homeNavigationFragment).currentDestination?.id) {
                in home -> {
                    finish()
                }
                in quiz -> {
                    val frag =
                        supportFragmentManager.findFragmentById(R.id.navHome) as NavHostFragment
                    (frag.childFragmentManager.fragments[0] as QuestionsFragment).showConfirmation()
                }
                else -> {
                    findNavController(R.id.homeNavigationFragment).navigateUp()
                }
            }
        }*/
        homeViewModel.profileImageToolbarLiveData.observe(this, EventObserver {
            updateProfileImage(it)
        })
        homeViewModel.getUser()
    }

    private fun updateProfileImage(image: String) {
        GlideApp.with(this).load(image).into(binding.includeToolbar.imageViewProfile)
    }
}