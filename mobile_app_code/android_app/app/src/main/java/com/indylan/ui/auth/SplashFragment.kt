package com.indylan.ui.auth

import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.fragment.app.viewModels
import androidx.navigation.fragment.FragmentNavigatorExtras
import androidx.navigation.fragment.findNavController
import com.indylan.data.model.result.EventObserver
import com.indylan.databinding.FragmentSplashBinding
import com.indylan.databinding.LayoutErrorBinding
import com.indylan.databinding.LayoutProgressBinding
import com.indylan.ui.base.BaseFragment
import com.indylan.ui.base.BaseViewModel
import dagger.hilt.android.AndroidEntryPoint

@AndroidEntryPoint
class SplashFragment : BaseFragment() {

    private val authenticationViewModel: AuthenticationViewModel by viewModels()
    private lateinit var binding: FragmentSplashBinding

    override fun getViewModel(): BaseViewModel = authenticationViewModel

    override fun onBackPress(): Boolean = false

    override fun getLoadingView(): LayoutProgressBinding? = null

    override fun getErrorView(): LayoutErrorBinding? = null

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        binding = FragmentSplashBinding.inflate(inflater, container, false).apply {
            lifecycleOwner = viewLifecycleOwner
        }
        return binding.root
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)
        authenticationViewModel.loginScreenLiveData.observe(viewLifecycleOwner, EventObserver {
            val extras = FragmentNavigatorExtras(
                binding.imageViewLogo to "imageViewLogo"
            )
            findNavController().navigate(
                SplashFragmentDirections.toLoginFragment(),
                extras
            )
        })
        authenticationViewModel.homeScreenLiveData.observe(viewLifecycleOwner, EventObserver {
            authorize()
        })
        authenticationViewModel.showNext()
    }
}