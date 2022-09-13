package com.indylan.ui.auth

import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.view.inputmethod.EditorInfo
import androidx.fragment.app.viewModels
import androidx.interpolator.view.animation.FastOutSlowInInterpolator
import androidx.navigation.fragment.findNavController
import androidx.transition.TransitionInflater
import com.indylan.R
import com.indylan.common.extensions.hideKeyBoard
import com.indylan.common.extensions.isValidEmail
import com.indylan.data.model.result.EventObserver
import com.indylan.databinding.FragmentLoginBinding
import com.indylan.databinding.LayoutErrorBinding
import com.indylan.databinding.LayoutProgressBinding
import com.indylan.ui.base.BaseFragment
import com.indylan.ui.base.BaseViewModel
import dagger.hilt.android.AndroidEntryPoint

@AndroidEntryPoint
class LoginFragment : BaseFragment() {

    //private val RC_SIGN_IN: Int = 1123
    private val viewModel: AuthenticationViewModel by viewModels()

    //private val callbackManager = CallbackManager.Factory.create()
    private lateinit var binding: FragmentLoginBinding

    override fun getViewModel(): BaseViewModel = viewModel

    override fun onBackPress(): Boolean {
        requireActivity().finish()
        return false
    }

    override fun getLoadingView(): LayoutProgressBinding? = null

    override fun getErrorView(): LayoutErrorBinding? = null

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        showTransition()
    }

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        binding = FragmentLoginBinding.inflate(inflater, container, false).apply {
            lifecycleOwner = viewLifecycleOwner
            textInputEditTextPassword.setOnEditorActionListener { _, actionId, _ ->
                if (actionId == EditorInfo.IME_ACTION_DONE) {
                    hideKeyBoard()
                    buttonLogin.callOnClick()
                    return@setOnEditorActionListener true
                }
                return@setOnEditorActionListener false
            }
            buttonLogin.setOnClickListener {
                hideKeyBoard()
                val email = textInputEditTextEmail.text.toString().trim()
                val password = textInputEditTextPassword.text.toString().trim()

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

                viewModel.login(email, password)
            }
            buttonRegister.setOnClickListener {
                findNavController().navigate(LoginFragmentDirections.toRegisterFragment())
            }
            buttonForgotPassword.setOnClickListener {
                findNavController().navigate(LoginFragmentDirections.toForgotPasswordFragment())
            }
            /*buttonGoogle.setOnClickListener {
                val gso = GoogleSignInOptions.Builder(GoogleSignInOptions.DEFAULT_SIGN_IN)
                    .requestEmail()
                    .build()
                val mGoogleSignInClient = GoogleSignIn.getClient(requireContext(), gso)
                mGoogleSignInClient.signOut()
                startActivityForResult(mGoogleSignInClient.signInIntent, RC_SIGN_IN)
            }
            buttonFacebook.setOnClickListener {
                LoginManager.getInstance()
                    .logInWithReadPermissions(this@LoginFragment, listOf("public_profile"))
            }*/
        }
        return binding.root
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)
        viewModel.homeScreenLiveData.observe(viewLifecycleOwner, EventObserver {
            authorize()
        })
        /*LoginManager.getInstance().registerCallback(
            callbackManager,
            object : FacebookCallback<LoginResult?> {
                override fun onSuccess(loginResult: LoginResult?) {
                    Timber.d("Facebook: ${loginResult?.accessToken?.userId}")
                    val profile = Profile.getCurrentProfile()
                    Timber.d("Profile: $profile")
                    showLoadingDialog()
                    val emailRequest = GraphRequest.newMeRequest(
                        loginResult?.accessToken
                    ) { `object`, response ->
                        dismissLoadingDialog()
                        if (`object` != null) {
                            val email = `object`.optString("email")
                            Timber.d("Email: $email")
                            viewModel.loginFacebook(
                                profile.firstName,
                                profile.lastName,
                                email.toString(),
                                profile.getProfilePictureUri(500, 500).toString(),
                                profile.id,
                            )
                        } else {
                            showMessage("Email address not found")
                        }
                    }
                    emailRequest.parameters = bundleOf("fields" to "id,name,email")
                    emailRequest.executeAsync()
                }

                override fun onCancel() {

                }

                override fun onError(exception: FacebookException) {
                    exception.printStackTrace()
                    showMessage(exception.message)
                }
            })*/
    }

    private fun showTransition() {
        val transition =
            TransitionInflater.from(requireContext()).inflateTransition(android.R.transition.move)
        transition.duration = resources.getInteger(R.integer.splash_logo_duration).toLong()
        transition.interpolator = FastOutSlowInInterpolator()
        //transition.interpolator = OvershootInterpolator()
        sharedElementEnterTransition = transition
    }

    /*override fun onActivityResult(requestCode: Int, resultCode: Int, data: Intent?) {
        callbackManager.onActivityResult(requestCode, resultCode, data)
        super.onActivityResult(requestCode, resultCode, data)
        if (requestCode == RC_SIGN_IN) {
            val task = GoogleSignIn.getSignedInAccountFromIntent(data)
            task.addOnCompleteListener {
                if (it.isSuccessful) {
                    it.result?.let {
                        viewModel.loginGoogle(
                            it.displayName.toString(),
                            it.email.toString(),
                            if (it.photoUrl != null) it.photoUrl.toString() else "",
                            it.id.toString()
                        )
                    }
                } else {
                    showMessage(it.exception?.message)
                }
            }
        }
    }*/
}