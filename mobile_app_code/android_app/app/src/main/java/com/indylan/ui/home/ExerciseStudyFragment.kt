package com.indylan.ui.home

import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.fragment.app.viewModels
import androidx.navigation.fragment.findNavController
import com.indylan.R
import com.indylan.common.extensions.goneView
import com.indylan.common.extensions.showView
import com.indylan.common.glide.GlideApp
import com.indylan.data.model.result.EventObserver
import com.indylan.databinding.FragmentExerciseStudyBinding
import com.indylan.databinding.LayoutErrorBinding
import com.indylan.databinding.LayoutProgressBinding
import com.indylan.ui.base.BaseFragment
import com.indylan.ui.base.BaseViewModel
import dagger.hilt.android.AndroidEntryPoint

@AndroidEntryPoint
class ExerciseStudyFragment : BaseFragment() {

    private val viewModel: HomeViewModel by viewModels()
    private lateinit var binding: FragmentExerciseStudyBinding

    private val supportLanguage by lazy {
        ExerciseStudyFragmentArgs.fromBundle(requireArguments()).supportLanguage
    }
    private val menuLanguage by lazy {
        ExerciseStudyFragmentArgs.fromBundle(requireArguments()).menuLanguage
    }
    private val targetLanguage by lazy {
        ExerciseStudyFragmentArgs.fromBundle(requireArguments()).targetLanguage
    }
    private val exerciseMode by lazy {
        ExerciseStudyFragmentArgs.fromBundle(requireArguments()).exerciseMode
    }
    private val category by lazy {
        ExerciseStudyFragmentArgs.fromBundle(requireArguments()).category
    }
    private val subcategory by lazy {
        ExerciseStudyFragmentArgs.fromBundle(requireArguments()).subcategory
    }
    private val exerciseType by lazy {
        ExerciseStudyFragmentArgs.fromBundle(requireArguments()).exerciseType
    }
    private val isTestMode by lazy {
        ExerciseStudyFragmentArgs.fromBundle(requireArguments()).isTestMode
    }
    private val questions by lazy {
        ExerciseStudyFragmentArgs.fromBundle(requireArguments()).questions
    }

    override fun getViewModel(): BaseViewModel = viewModel

    override fun onBackPress(): Boolean = true

    override fun getLoadingView(): LayoutProgressBinding = binding.includeProgress

    override fun getErrorView(): LayoutErrorBinding = binding.includeError

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View {
        binding = FragmentExerciseStudyBinding.inflate(inflater, container, false).apply {
            lifecycleOwner = viewLifecycleOwner
        }
        return binding.root
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)
        setTitle(exerciseType.name.toString())
        viewModel.exerciseLiveData.observe(viewLifecycleOwner, EventObserver {
            getLoadingView().progressBar.goneView()
            binding.buttonTapToContinue.showView()
            getErrorView().linearLayoutError.goneView()
            val exercise = viewModel.parseTextComprehensionExercises(it)
            if (exercise.isNotEmpty()) {
                GlideApp.with(this).load(exercise[0].image).into(binding.imageView)
                binding.textViewTitle.text = exercise[0].title
                binding.textViewParagraph.text = exercise[0].paragraph
                binding.textViewLink.text = exercise[0].link
                binding.buttonTapToContinue.setOnClickListener {
                    findNavController().navigate(
                        ExerciseStudyFragmentDirections.toExerciseFragment(
                            supportLanguage,
                            menuLanguage,
                            targetLanguage,
                            exerciseMode,
                            category,
                            subcategory,
                            exerciseType,
                            exercise[0],
                            isTestMode
                        )
                    )
                }
                binding.textViewLink.setOnClickListener {
                    findNavController().navigate(
                        ExerciseStudyFragmentDirections.toWeb(
                            exerciseType.name.toString(),
                            exercise[0].link.toString()
                        )
                    )
                }
            } else {
                getLoadingView().progressBar.goneView()
                binding.buttonTapToContinue.goneView()
                getErrorView().linearLayoutError.showView()
                getErrorView().textViewError.text = getString(R.string.no_data)
            }
        })
        viewModel.emptyExerciseLiveData.observe(viewLifecycleOwner, EventObserver {
            binding.buttonTapToContinue.goneView()
        })
        if (isTestMode) {
            viewModel.fetchTestExercise(
                supportLanguage,
                targetLanguage,
                exerciseMode,
                exerciseType,
                questions
            )
        } else {
            viewModel.fetchExercise(
                supportLanguage,
                targetLanguage,
                exerciseMode,
                category!!,
                subcategory!!,
                exerciseType
            )
        }
    }
}