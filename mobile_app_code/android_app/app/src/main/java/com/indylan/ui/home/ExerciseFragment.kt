package com.indylan.ui.home

import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.fragment.app.viewModels
import androidx.navigation.fragment.findNavController
import androidx.viewpager2.adapter.FragmentStateAdapter
import androidx.viewpager2.widget.ViewPager2
import com.google.gson.Gson
import com.indylan.R
import com.indylan.common.extensions.goneView
import com.indylan.common.extensions.hideKeyBoard
import com.indylan.common.extensions.showView
import com.indylan.data.model.ExerciseTypeEnum
import com.indylan.data.model.result.EventObserver
import com.indylan.databinding.FragmentExerciseBinding
import com.indylan.databinding.LayoutErrorBinding
import com.indylan.databinding.LayoutProgressBinding
import com.indylan.ui.base.BaseFragment
import com.indylan.ui.base.BaseViewModel
import com.indylan.ui.home.exercise.*
import com.indylan.ui.home.exercise.base.BaseExerciseFragment
import dagger.hilt.android.AndroidEntryPoint
import javax.inject.Inject

@AndroidEntryPoint
class ExerciseFragment : BaseFragment() {

    @Inject
    lateinit var gson: Gson

    private val viewModel: HomeViewModel by viewModels()
    private lateinit var binding: FragmentExerciseBinding
    private val callback = object : ViewPager2.OnPageChangeCallback() {
        override fun onPageSelected(position: Int) {
            super.onPageSelected(position)
            val fragment = childFragmentManager.findFragmentByTag("f$position")
            if (fragment != null) {
                //Timber.d("Fragment found")
                if (fragment is BaseExerciseFragment) {
                    fragment.onFocusGained()
                }
            } /*else {
                Timber.d("Fragment not found!")
            }*/
        }
    }

    private val supportLanguage by lazy {
        ExerciseFragmentArgs.fromBundle(requireArguments()).supportLanguage
    }
    private val menuLanguage by lazy {
        ExerciseFragmentArgs.fromBundle(requireArguments()).menuLanguage
    }
    private val targetLanguage by lazy {
        ExerciseFragmentArgs.fromBundle(requireArguments()).targetLanguage
    }
    private val exerciseMode by lazy {
        ExerciseFragmentArgs.fromBundle(requireArguments()).exerciseMode
    }
    private val category by lazy {
        ExerciseFragmentArgs.fromBundle(requireArguments()).category
    }
    private val subcategory by lazy {
        ExerciseFragmentArgs.fromBundle(requireArguments()).subcategory
    }
    private val exerciseType by lazy {
        ExerciseFragmentArgs.fromBundle(requireArguments()).exerciseType
    }
    private val exerciseTextComprehension by lazy {
        ExerciseFragmentArgs.fromBundle(requireArguments()).exerciseTextComprehension
    }
    private val isTestMode by lazy {
        ExerciseFragmentArgs.fromBundle(requireArguments()).isTestMode
    }
    private val questions by lazy {
        ExerciseFragmentArgs.fromBundle(requireArguments()).questions
    }
    private var totalScore = 0
    private var myScore = 0

    override fun getViewModel(): BaseViewModel = viewModel

    override fun onBackPress(): Boolean = true

    override fun getLoadingView(): LayoutProgressBinding = binding.includeProgress

    override fun getErrorView(): LayoutErrorBinding = binding.includeError

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View {
        binding = FragmentExerciseBinding.inflate(inflater, container, false).apply {
            lifecycleOwner = viewLifecycleOwner
            buttonTapToContinue.setOnClickListener {
                nextQuestion()
            }
            viewPager.isUserInputEnabled = false
            viewPager.offscreenPageLimit = 3
            viewPager.registerOnPageChangeCallback(callback)
        }
        return binding.root
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)
        if (!isTestMode) {
            setTitle(category?.name.toString())
        }
        viewModel.exerciseLiveData.observe(viewLifecycleOwner, EventObserver {
            getLoadingView().progressBar.goneView()
            binding.viewPager.showView()
            binding.frameBottomButton.showView()
            getErrorView().linearLayoutError.goneView()
            resetScore()
            binding.viewPager.adapter = generateAdapter(it)
        })
        viewModel.emptyExerciseLiveData.observe(viewLifecycleOwner, EventObserver {
            getLoadingView().progressBar.goneView()
            binding.viewPager.goneView()
            binding.frameBottomButton.goneView()
            getErrorView().linearLayoutError.showView()
            getErrorView().textViewError.text = it
        })
        if (exerciseTextComprehension != null) {
            getLoadingView().progressBar.goneView()
            binding.viewPager.showView()
            binding.frameBottomButton.showView()
            getErrorView().linearLayoutError.goneView()
            resetScore()
            val json = gson.toJson(exerciseTextComprehension)
            binding.viewPager.adapter = generateAdapter(json)
        } else {
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

    private fun generateAdapter(response: String): FragmentStateAdapter {
        return when (exerciseType.parseExerciseType()) {
            ExerciseTypeEnum.TRANSLATION -> {
                val exercises = viewModel.parseTranslationExercise(response)
                totalScore = exercises.size
                TranslationPagerAdapter(exercises, this@ExerciseFragment)
            }
            ExerciseTypeEnum.MULTI_CHOICE_IMAGE -> {
                val exercises = viewModel.parseMultipleChoiceImageExercise(response)
                totalScore = exercises.size
                MultipleChoiceImagePagerAdapter(exercises, this@ExerciseFragment)
            }
            ExerciseTypeEnum.MULTI_CHOICE_WORDS -> {
                val exercises = viewModel.parseMultipleChoiceImageExercise(response)
                totalScore = exercises.size
                MultipleChoiceWordPagerAdapter(exercises, false, this@ExerciseFragment)
            }
            ExerciseTypeEnum.CHOOSE_LETTERS -> {
                val exercises = viewModel.parseChooseLettersExercise(response)
                totalScore = exercises.size
                ChooseLettersPagerAdapter(exercises, this@ExerciseFragment)
            }
            ExerciseTypeEnum.WRITE_WORD -> {
                val exercises = viewModel.parseWriteWordExercise(response)
                totalScore = exercises.size
                /*findNavController().navigate(
                    ExerciseFragmentDirections.toKeyboardDialogFragment(targetLanguage)
                )*/
                WriteWordPagerAdapter(exercises, this@ExerciseFragment)
            }
            ExerciseTypeEnum.CHOOSE_IMAGE -> {
                val exercises = viewModel.parsePictureAnswerExercises(response)
                totalScore = exercises.size
                PictureAnswerPagerAdapter(exercises, this@ExerciseFragment)
            }
            ExerciseTypeEnum.MATCHING -> {
                val exercises = viewModel.parseMatchAnswerExercises(response)
                totalScore = exercises.size
                MatchingPagerAdapter(exercises, this@ExerciseFragment)
            }
            ExerciseTypeEnum.FLASH_CARDS_IMAGE, ExerciseTypeEnum.FLASH_CARDS_TEXT -> {
                val exercises = viewModel.parseFlashCardExercise(response)
                totalScore = exercises.size
                FlashCardPagerAdapter(exerciseType, exercises, this@ExerciseFragment)
            }
            ExerciseTypeEnum.LISTENING -> {
                val exercises = viewModel.parseListeningExercise(response)
                totalScore = exercises.size
                ListeningPagerAdapter(exercises, this@ExerciseFragment)
            }
            ExerciseTypeEnum.TEXT_CHAT_VIEW_ONLY, ExerciseTypeEnum.MULTIPLE_CHOICE_CHAT_SELECTION -> {
                val exercises = viewModel.parseDialogExercise(response)
                totalScore = exercises.size
                DialogPagerAdapter(exerciseType, exercises, this@ExerciseFragment)
            }
            ExerciseTypeEnum.FILL_GAP -> {
                val exercises = viewModel.parseFillGapExercise(response)
                totalScore = exercises.size
                /*findNavController().navigate(
                    ExerciseFragmentDirections.toKeyboardDialogFragment(targetLanguage)
                )*/
                FillGapPagerAdapter(exercises, this@ExerciseFragment)
            }
            ExerciseTypeEnum.TEXT_COMPREHENSION -> {
                val exercise = viewModel.parseTextComprehensionExercise(response)
                totalScore = exercise.questions?.size ?: 0
                MultipleChoiceWordPagerAdapter(
                    exercise.questions ?: emptyList(),
                    false, this@ExerciseFragment
                )
            }
            ExerciseTypeEnum.AURAL_NUMBERS -> {
                val exercises = viewModel.parseListeningExercise(response)
                totalScore = exercises.size
                ListeningPagerAdapter(exercises, this@ExerciseFragment)
            }
            ExerciseTypeEnum.AURAL_SENTENCES -> {
                val exercises = viewModel.parseMultipleChoiceImageExercise(response)
                totalScore = exercises.size
                MultipleChoiceWordPagerAdapter(exercises, true, this@ExerciseFragment)
            }
            ExerciseTypeEnum.AURAL_WORDS -> {
                val exercises = viewModel.parseMultipleChoiceImageExercise(response)
                totalScore = exercises.size
                MultipleChoiceWordPagerAdapter(exercises, true, this@ExerciseFragment)
            }
            else -> {
                totalScore = 0
                OtherPagerAdapter(this@ExerciseFragment)
            }
        }
    }

    override fun onDestroyView() {
        hideKeyBoard()
        binding.viewPager.unregisterOnPageChangeCallback(callback)
        super.onDestroyView()
    }

    fun showButton(isEnabled: Boolean = true, text: String? = null) {
        binding.textViewMessage.goneView()
        binding.buttonTapToContinue.isEnabled = isEnabled
        if (text != null) {
            binding.buttonTapToContinue.text = text
        } else {
            binding.buttonTapToContinue.text = getString(R.string.tap_to_continue)
        }
        binding.buttonTapToContinue.showView()
    }

    fun showMessageUI(message: String) {
        binding.buttonTapToContinue.goneView()
        binding.textViewMessage.showView()
        binding.textViewMessage.text = message
    }

    fun showExerciseTypeName() {
        showMessageUI(exerciseType.name.toString())
    }

    fun nextQuestion() {
        if (binding.viewPager.currentItem + 1 == binding.viewPager.adapter?.itemCount) {
            binding.viewPager.adapter = null
            findNavController().navigate(
                ExerciseFragmentDirections.toTaskCompleteFragment(
                    supportLanguage,
                    menuLanguage,
                    targetLanguage,
                    exerciseMode,
                    category,
                    subcategory,
                    exerciseType,
                    totalScore,
                    myScore,
                    isTestMode
                )
            )
        } else {
            binding.viewPager.setCurrentItem(binding.viewPager.currentItem + 1, true)
        }
    }

    fun increaseScore() {
        myScore++
    }

    fun decreaseScore() {
        myScore--
    }

    fun resetScore() {
        myScore = 0
    }
}