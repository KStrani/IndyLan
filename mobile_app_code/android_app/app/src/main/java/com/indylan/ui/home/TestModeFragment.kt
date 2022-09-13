package com.indylan.ui.home

import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.fragment.app.viewModels
import androidx.navigation.fragment.findNavController
import com.google.android.material.dialog.MaterialAlertDialogBuilder
import com.indylan.R
import com.indylan.data.model.ExerciseMode
import com.indylan.data.model.ExerciseType
import com.indylan.data.model.ExerciseTypeEnum
import com.indylan.data.model.result.EventObserver
import com.indylan.databinding.FragmentExerciseModeTestBinding
import com.indylan.databinding.LayoutErrorBinding
import com.indylan.databinding.LayoutProgressBinding
import com.indylan.ui.base.BaseFragment
import com.indylan.ui.base.BaseViewModel
import dagger.hilt.android.AndroidEntryPoint

@AndroidEntryPoint
class TestModeFragment : BaseFragment() {

    private val viewModel: HomeViewModel by viewModels()
    private lateinit var binding: FragmentExerciseModeTestBinding
    private var exerciseTypes: List<ExerciseType> = emptyList()
    private var numberOfQuestions: List<Int> = listOf(10, 20, 30, 40, 50, 60, 70, 80, 90, 100)

    private var selectedExerciseMode: ExerciseMode? = null
    private var selectedExerciseType: ExerciseType? = null
    private var selectedNumberOfQuestions: Int = 0

    private val supportLanguage by lazy {
        TestModeFragmentArgs.fromBundle(requireArguments()).supportLanguage
    }
    private val menuLanguage by lazy {
        TestModeFragmentArgs.fromBundle(requireArguments()).menuLanguage
    }
    private val targetLanguage by lazy {
        TestModeFragmentArgs.fromBundle(requireArguments()).targetLanguage
    }

    override fun getViewModel(): BaseViewModel = viewModel

    override fun onBackPress(): Boolean = true

    override fun getLoadingView(): LayoutProgressBinding? = null

    override fun getErrorView(): LayoutErrorBinding? = null

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View {
        binding = FragmentExerciseModeTestBinding.inflate(inflater, container, false).apply {
            lifecycleOwner = viewLifecycleOwner
            val exerciseModes = viewModel.getExerciseModesFromFile().dropLast(1)
            textViewExerciseMode.setOnClickListener {
                val dialog = MaterialAlertDialogBuilder(requireContext())
                dialog.setItems(
                    exerciseModes.map { it.name }.toTypedArray()
                ) { dialogInterface, position ->
                    selectedExerciseMode = exerciseModes[position]
                    dialogInterface.dismiss()
                    textViewExerciseMode.text = selectedExerciseMode?.name
                    resetExerciseType()
                    resetNumberOfQuestions()
                    viewModel.fetchTestExerciseTypes(
                        supportLanguage,
                        targetLanguage,
                        exerciseModes[position]
                    )
                }
                dialog.show()
            }
            textViewExerciseType.setOnClickListener {
                if (exerciseTypes.isNotEmpty()) {
                    val dialog = MaterialAlertDialogBuilder(requireContext())
                    dialog.setItems(
                        exerciseTypes.map { it.name }.toTypedArray()
                    ) { dialogInterface, position ->
                        selectedExerciseType = exerciseTypes[position]
                        dialogInterface.dismiss()
                        textViewExerciseType.text = selectedExerciseType?.name
                        resetNumberOfQuestions()
                    }
                    dialog.show()
                }
            }
            textViewNumberOfQuestions.setOnClickListener {
                if (selectedExerciseType != null) {
                    val dialog = MaterialAlertDialogBuilder(requireContext())
                    dialog.setItems(
                        numberOfQuestions.map { it.toString() }.toTypedArray()
                    ) { dialogInterface, position ->
                        selectedNumberOfQuestions = numberOfQuestions[position]
                        dialogInterface.dismiss()
                        textViewNumberOfQuestions.text = selectedNumberOfQuestions.toString()
                        buttonStart.isEnabled = true
                    }
                    dialog.show()
                }
            }
            buttonStart.setOnClickListener {
                if (selectedExerciseMode != null && selectedExerciseType != null) {
                    if (selectedExerciseType?.parseExerciseType() == ExerciseTypeEnum.TEXT_COMPREHENSION) {
                        findNavController().navigate(
                            TestModeFragmentDirections.toExerciseStudyFragment(
                                supportLanguage,
                                menuLanguage,
                                targetLanguage,
                                selectedExerciseMode!!,
                                null,
                                null,
                                selectedExerciseType!!,
                                true,
                                selectedNumberOfQuestions
                            )
                        )
                    } else {
                        findNavController().navigate(
                            ExerciseTypeFragmentDirections.toExerciseFragment(
                                supportLanguage,
                                menuLanguage,
                                targetLanguage,
                                selectedExerciseMode!!,
                                null,
                                null,
                                selectedExerciseType!!,
                                null,
                                true,
                                selectedNumberOfQuestions
                            )
                        )
                    }
                }
            }
        }
        return binding.root
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)
        setTitle("Test")
        viewModel.exerciseTypesLiveData.observe(viewLifecycleOwner, EventObserver {
            exerciseTypes = it
        })
        viewModel.emptyExerciseTypesLiveData.observe(viewLifecycleOwner, EventObserver {
            exerciseTypes = emptyList()
            showMessage(it)
        })
    }

    private fun resetExerciseType() {
        binding.buttonStart.isEnabled = false
        selectedExerciseType = null
        binding.textViewExerciseType.text = getString(R.string.choose_exercise_type)
    }

    private fun resetNumberOfQuestions() {
        binding.buttonStart.isEnabled = false
        selectedNumberOfQuestions = 0
        binding.textViewNumberOfQuestions.text = getString(R.string.choose_number_of_questions)
    }
}