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
import com.indylan.data.model.ExerciseTypeEnum
import com.indylan.data.model.result.EventObserver
import com.indylan.databinding.FragmentExerciseTypeBinding
import com.indylan.databinding.LayoutErrorBinding
import com.indylan.databinding.LayoutProgressBinding
import com.indylan.ui.base.BaseFragment
import com.indylan.ui.base.BaseViewModel
import com.indylan.widget.MarginItemDecoration
import dagger.hilt.android.AndroidEntryPoint

@AndroidEntryPoint
class ExerciseTypeFragment : BaseFragment() {

    private val viewModel: HomeViewModel by viewModels()
    private lateinit var binding: FragmentExerciseTypeBinding
    private val adapter by lazy {
        ExerciseTypeAdapter(requireContext(), viewLifecycleOwner) {
            if (it.parseExerciseType() == ExerciseTypeEnum.TEXT_COMPREHENSION) {
                findNavController().navigate(
                    ExerciseTypeFragmentDirections.toExerciseStudyFragment(
                        supportLanguage,
                        menuLanguage,
                        targetLanguage,
                        exerciseMode,
                        category,
                        subcategory,
                        it,
                        false
                    )
                )
            } else {
                findNavController().navigate(
                    ExerciseTypeFragmentDirections.toExerciseFragment(
                        supportLanguage,
                        menuLanguage,
                        targetLanguage,
                        exerciseMode,
                        category,
                        subcategory,
                        it,
                        null,
                        false
                    )
                )
            }
        }
    }
    private val supportLanguage by lazy {
        ExerciseTypeFragmentArgs.fromBundle(requireArguments()).supportLanguage
    }
    private val menuLanguage by lazy {
        ExerciseTypeFragmentArgs.fromBundle(requireArguments()).menuLanguage
    }
    private val targetLanguage by lazy {
        ExerciseTypeFragmentArgs.fromBundle(requireArguments()).targetLanguage
    }
    private val exerciseMode by lazy {
        ExerciseTypeFragmentArgs.fromBundle(requireArguments()).exerciseMode
    }
    private val category by lazy {
        ExerciseTypeFragmentArgs.fromBundle(requireArguments()).category
    }
    private val subcategory by lazy {
        ExerciseTypeFragmentArgs.fromBundle(requireArguments()).subcategory
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
        binding = FragmentExerciseTypeBinding.inflate(inflater, container, false).apply {
            lifecycleOwner = viewLifecycleOwner
            recyclerViewExerciseType.adapter = adapter
            recyclerViewExerciseType.addItemDecoration(
                MarginItemDecoration(
                    resources.getDimension(R.dimen.margin_recyclerview).toInt()
                )
            )
        }
        return binding.root
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)
        setTitle(getString(R.string.choose_exercise_type), subtitle = exerciseMode.name)
        viewModel.exerciseTypesLiveData.observe(viewLifecycleOwner, EventObserver {
            binding.recyclerViewExerciseType.showView()
            adapter.submitList(it)
        })
        viewModel.emptyExerciseTypesLiveData.observe(viewLifecycleOwner, EventObserver {
            binding.recyclerViewExerciseType.goneView()
        })
        binding.recyclerViewExerciseType.goneView()
        viewModel.fetchExerciseTypes(supportLanguage, menuLanguage, subcategory)
    }
}