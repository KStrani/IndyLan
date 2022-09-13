package com.indylan.ui.home

import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.fragment.app.viewModels
import androidx.navigation.fragment.findNavController
import com.indylan.R
import com.indylan.common.extensions.openPlayStore
import com.indylan.databinding.FragmentTaskCompleteBinding
import com.indylan.databinding.LayoutErrorBinding
import com.indylan.databinding.LayoutProgressBinding
import com.indylan.ui.base.BaseFragment
import com.indylan.ui.base.BaseViewModel
import dagger.hilt.android.AndroidEntryPoint

@AndroidEntryPoint
class TaskCompleteFragment : BaseFragment() {

    private val viewModel: HomeViewModel by viewModels()
    private lateinit var binding: FragmentTaskCompleteBinding

    private val supportLanguage by lazy {
        TaskCompleteFragmentArgs.fromBundle(requireArguments()).supportLanguage
    }
    private val menuLanguage by lazy {
        TaskCompleteFragmentArgs.fromBundle(requireArguments()).menuLanguage
    }
    private val targetLanguage by lazy {
        TaskCompleteFragmentArgs.fromBundle(requireArguments()).targetLanguage
    }
    private val exerciseMode by lazy {
        TaskCompleteFragmentArgs.fromBundle(requireArguments()).exerciseMode
    }
    private val category by lazy {
        TaskCompleteFragmentArgs.fromBundle(requireArguments()).category
    }
    private val subcategory by lazy {
        TaskCompleteFragmentArgs.fromBundle(requireArguments()).subcategory
    }
    private val exerciseType by lazy {
        TaskCompleteFragmentArgs.fromBundle(requireArguments()).exerciseType
    }
    private val totalScore by lazy {
        TaskCompleteFragmentArgs.fromBundle(requireArguments()).totalScore
    }
    private val myScore by lazy {
        TaskCompleteFragmentArgs.fromBundle(requireArguments()).myScore
    }
    private val isTestMode by lazy {
        TaskCompleteFragmentArgs.fromBundle(requireArguments()).isTestMode
    }

    override fun getViewModel(): BaseViewModel = viewModel

    override fun onBackPress(): Boolean = false

    override fun getLoadingView(): LayoutProgressBinding? = null

    override fun getErrorView(): LayoutErrorBinding? = null

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View {
        binding = FragmentTaskCompleteBinding.inflate(inflater, container, false).apply {
            lifecycleOwner = viewLifecycleOwner
            textViewHeading.text = getString(R.string.congratulations)
            textViewMessage.text = getString(R.string.task_completed_successfully)
            textViewExerciseType.text = exerciseType.name
            buttonRateUs.setOnClickListener {
                requireActivity().openPlayStore()
            }
            buttonReturn.setOnClickListener {
                if (isTestMode) {
                    findNavController().popBackStack(R.id.exerciseModeFragment, false)
                } else {
                    findNavController().popBackStack(R.id.exerciseTypeFragment, false)
                }
            }
            buttonRetry.setOnClickListener {
                if (isTestMode) {
                    findNavController().popBackStack(R.id.testModeFragment, false)
                } else {
                    findNavController().popBackStack(R.id.exerciseFragment, false)
                }
            }
            buttonHome.setOnClickListener {
                findNavController().popBackStack(R.id.supportLanguageFragment, false)
            }
            /*when (exerciseType.parseExerciseType()) {
                ExerciseTypeEnum.FLASH_CARDS_IMAGE, ExerciseTypeEnum.FLASH_CARDS_TEXT, ExerciseTypeEnum.TEXT_CHAT_VIEW_ONLY -> {
                    textViewTotalScore.hideView()
                }
                else -> {
                    textViewTotalScore.showView()
                    textViewTotalScore.text =
                        getString(R.string.total_score_d_d, myScore, totalScore)
                }
            }
            if (isTestMode) {
                textViewTotalScore.hideView()
            }*/
        }
        return binding.root
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)
        setTitle(getString(R.string.congratulations), showBack = false, showProfile = false)
        if (!isTestMode) {
            viewModel.submitScore(
                supportLanguage,
                targetLanguage,
                exerciseMode,
                category!!,
                subcategory!!,
                exerciseType,
                totalScore,
                myScore
            )
        }
    }
}