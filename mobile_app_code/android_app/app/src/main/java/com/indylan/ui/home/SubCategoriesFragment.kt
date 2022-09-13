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
import com.indylan.databinding.FragmentCategoriesBinding
import com.indylan.databinding.LayoutErrorBinding
import com.indylan.databinding.LayoutProgressBinding
import com.indylan.ui.base.BaseFragment
import com.indylan.ui.base.BaseViewModel
import com.indylan.widget.MarginItemDecoration
import dagger.hilt.android.AndroidEntryPoint

@AndroidEntryPoint
class SubCategoriesFragment : BaseFragment() {

    private val viewModel: HomeViewModel by viewModels()
    private lateinit var binding: FragmentCategoriesBinding
    private val adapter by lazy {
        SubcategoryAdapter(requireContext(), viewLifecycleOwner) {
            findNavController().navigate(
                SubCategoriesFragmentDirections.toExerciseTypeFragment(
                    supportLanguage, menuLanguage, targetLanguage, exerciseMode, category, it
                )
            )
        }
    }
    private val supportLanguage by lazy {
        SubCategoriesFragmentArgs.fromBundle(requireArguments()).supportLanguage
    }
    private val menuLanguage by lazy {
        SubCategoriesFragmentArgs.fromBundle(requireArguments()).menuLanguage
    }
    private val targetLanguage by lazy {
        SubCategoriesFragmentArgs.fromBundle(requireArguments()).targetLanguage
    }
    private val exerciseMode by lazy {
        SubCategoriesFragmentArgs.fromBundle(requireArguments()).exerciseMode
    }
    private val category by lazy {
        SubCategoriesFragmentArgs.fromBundle(requireArguments()).category
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
        binding = FragmentCategoriesBinding.inflate(inflater, container, false).apply {
            lifecycleOwner = viewLifecycleOwner
            recyclerViewCategories.adapter = adapter
            recyclerViewCategories.addItemDecoration(
                MarginItemDecoration(
                    resources.getDimension(R.dimen.margin_recyclerview).toInt()
                )
            )
        }
        return binding.root
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)
        setTitle(getString(R.string.select_a_sub_category), subtitle = exerciseMode.name)
        viewModel.subcategoriesLiveData.observe(viewLifecycleOwner, EventObserver {
            binding.recyclerViewCategories.showView()
            adapter.submitList(it)
        })
        viewModel.emptySubcategoriesLiveData.observe(viewLifecycleOwner, EventObserver {
            binding.recyclerViewCategories.goneView()
        })
        binding.recyclerViewCategories.goneView()
        viewModel.fetchSubCategories(supportLanguage, menuLanguage, category)
    }
}