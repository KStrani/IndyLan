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
import com.indylan.data.model.result.EventObserver
import com.indylan.databinding.FragmentExerciseModeBinding
import com.indylan.databinding.LayoutErrorBinding
import com.indylan.databinding.LayoutProgressBinding
import com.indylan.ui.base.BaseFragment
import com.indylan.ui.base.BaseViewModel
import com.indylan.widget.MarginItemDecoration
import dagger.hilt.android.AndroidEntryPoint

@AndroidEntryPoint
class ExerciseModeFragment : BaseFragment() {

    private val viewModel: HomeViewModel by viewModels()
    private lateinit var binding: FragmentExerciseModeBinding
    private val adapter by lazy {
        ExerciseModeAdapter(requireContext(), viewLifecycleOwner) {
            if (it.isTest()) {
                findNavController().navigate(
                    ExerciseModeFragmentDirections.toTestModeFragment(
                        supportLanguage, menuLanguage, targetLanguage
                    )
                )
            } else {
                findNavController().navigate(
                    ExerciseModeFragmentDirections.toCategoriesFragment(
                        supportLanguage, menuLanguage, targetLanguage, it
                    )
                )
            }
        }
    }
    private val supportLanguage by lazy {
        ExerciseModeFragmentArgs.fromBundle(requireArguments()).supportLanguage
    }
    private val menuLanguage by lazy {
        ExerciseModeFragmentArgs.fromBundle(requireArguments()).menuLanguage
    }
    private val targetLanguage by lazy {
        ExerciseModeFragmentArgs.fromBundle(requireArguments()).targetLanguage
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
        binding = FragmentExerciseModeBinding.inflate(inflater, container, false).apply {
            lifecycleOwner = viewLifecycleOwner
            recyclerViewExerciseMode.adapter = adapter
            recyclerViewExerciseMode.addItemDecoration(
                MarginItemDecoration(
                    resources.getDimension(R.dimen.margin_recyclerview).toInt()
                )
            )
        }
        return binding.root
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)
        setTitle(getString(R.string.select_exercise_mode))
        viewModel.exerciseModesLiveData.observe(viewLifecycleOwner, EventObserver {
            binding.recyclerViewExerciseMode.showView()
            val modes = it.filter {
                !it.isTest()
            }
            adapter.submitList(modes)
        })
        viewModel.emptyExerciseModesLiveData.observe(viewLifecycleOwner, EventObserver {
            binding.recyclerViewExerciseMode.goneView()
        })
        binding.recyclerViewExerciseMode.goneView()
        viewModel.fetchExerciseModes(supportLanguage)
    }
}