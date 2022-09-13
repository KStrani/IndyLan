package com.indylan.ui.auth

import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.fragment.app.viewModels
import androidx.navigation.fragment.findNavController
import com.indylan.BuildConfig
import com.indylan.R
import com.indylan.common.extensions.hideKeyBoard
import com.indylan.common.extensions.isValidEmail
import com.indylan.data.model.LoginType
import com.indylan.data.model.result.EventObserver
import com.indylan.databinding.FragmentRegisterBinding
import com.indylan.databinding.LayoutErrorBinding
import com.indylan.databinding.LayoutProgressBinding
import com.indylan.ui.base.BaseFragment
import com.indylan.ui.base.BaseViewModel
import dagger.hilt.android.AndroidEntryPoint

@AndroidEntryPoint
class RegisterFragment : BaseFragment() {

    private val viewModel: AuthenticationViewModel by viewModels()
    private var imagePath: String? = null
    private lateinit var binding: FragmentRegisterBinding

    override fun getViewModel(): BaseViewModel = viewModel

    override fun onBackPress(): Boolean = true

    override fun getLoadingView(): LayoutProgressBinding? = null

    override fun getErrorView(): LayoutErrorBinding? = null

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        binding = FragmentRegisterBinding.inflate(inflater, container, false).apply {
            lifecycleOwner = viewLifecycleOwner
            buttonRegister.setOnClickListener {
                hideKeyBoard()
                val name = textInputEditTextName.text.toString().trim()
                val email = textInputEditTextEmail.text.toString().trim()
                val password = textInputEditTextPassword.text.toString().trim()
                val confirmPassword = textInputEditTextConfirmPassword.text.toString().trim()

                if (name.isEmpty()) {
                    showMessage(getString(R.string.validation_empty_name))
                    return@setOnClickListener
                }

                if (email.isEmpty()) {
                    showMessage(getString(R.string.validation_empty_email))
                    return@setOnClickListener
                }

                if (!email.isValidEmail()) {
                    showMessage(getString(R.string.validation_invalid_email))
                    return@setOnClickListener
                }

                if (password.isEmpty()) {
                    showMessage(getString(R.string.validation_empty_password))
                    return@setOnClickListener
                }

                if (confirmPassword.isEmpty()) {
                    showMessage(getString(R.string.validation_empty_confirm_password))
                    return@setOnClickListener
                }

                if (password != confirmPassword) {
                    showMessage(getString(R.string.validation_password_mismatch))
                    return@setOnClickListener
                }

                viewModel.register(name, email, password, LoginType.NORMAL, imagePath)
            }
            buttonPrivacyPolicy.setOnClickListener {
                findNavController().navigate(
                    RegisterFragmentDirections.toWeb(
                        buttonPrivacyPolicy.text.toString(),
                        BuildConfig.URL_PRIVACY_POLICY
                    )
                )
            }
            buttonTerms.setOnClickListener {
                findNavController().navigate(
                    RegisterFragmentDirections.toWeb(
                        buttonTerms.text.toString(),
                        BuildConfig.URL_TERMS
                    )
                )
            }
        }
        return binding.root
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)
        setTitle(getString(R.string.registration), showProfile = false)
        viewModel.homeScreenLiveData.observe(viewLifecycleOwner, EventObserver {
            authorize()
        })
    }
}