package com.indylan.ui.home.exercise

import android.graphics.drawable.TransitionDrawable
import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.core.content.res.ResourcesCompat
import androidx.core.view.doOnLayout
import androidx.core.view.isVisible
import androidx.core.widget.doOnTextChanged
import androidx.fragment.app.Fragment
import androidx.navigation.fragment.findNavController
import androidx.viewpager2.adapter.FragmentStateAdapter
import com.indylan.R
import com.indylan.common.extensions.showKeyBoard
import com.indylan.data.model.ExerciseFillGap
import com.indylan.databinding.FragmentExerciseFillGapBinding
import com.indylan.ui.home.ExerciseFragment
import com.indylan.ui.home.ExerciseFragmentDirections
import com.indylan.ui.home.HomeActivity
import com.indylan.ui.home.exercise.base.BaseExerciseFragment

class FillGapExerciseFragment : BaseExerciseFragment() {

    lateinit var binding: FragmentExerciseFillGapBinding
    lateinit var exerciseFillGap: ExerciseFillGap
    private var answeredWrong = false
    private var isAnimating = false
    private var wrongCharacterCount = 0

    override fun onFocusGained() {
        view?.doOnLayout {
            (parentFragment as ExerciseFragment).showExerciseTypeName()
            binding.editTextAnswer.requestFocus()
            showKeyBoard()
        }
    }

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View {
        binding = FragmentExerciseFillGapBinding.inflate(inflater, container, false).apply {
            lifecycleOwner = viewLifecycleOwner
            textViewQuestion.text = createQuestionString(editTextAnswer.text.toString())
            val wrongTransition = ResourcesCompat.getDrawable(
                resources,
                R.drawable.bg_wrong_answer_transition,
                null
            ) as TransitionDrawable?
            editTextAnswer.background = wrongTransition
            editTextAnswer.doOnTextChanged { text, _, before, count ->
                //Timber.d("Answer: $text, $start, $before, $count")
                if (count - before > 1) {
                    editTextAnswer.text = null
                } else {
                    if (text != null) {

                        // Check if entered text length is less than answer length
                        val substring = if (text.length <= exerciseFillGap.options?.length ?: 0)
                            exerciseFillGap.options?.substring(0, text.length).toString()
                        else
                            exerciseFillGap.options

                        //Timber.d("Substring: $substring")
                        if (!text.toString().equals(substring, ignoreCase = true)) {
                            val newSubstring = text.substring(0, text.length - 1)
                            //Timber.d("New Substring: $newSubstring")
                            editTextAnswer.setText(newSubstring)
                            editTextAnswer.setSelection(newSubstring.length)
                            answeredWrong = true
                            wrongCharacterCount++
                            animateWrongAnswer()
                            if (wrongCharacterCount >= 5) {
                                showHintError()
                            }
                        } else {
                            textViewQuestion.text = createQuestionString(text.toString())
                            if (text.toString()
                                    .equals(exerciseFillGap.options, ignoreCase = true)
                            ) {
                                if (!answeredWrong) {
                                    (parentFragment as ExerciseFragment).increaseScore()
                                }
                                (parentFragment as ExerciseFragment).nextQuestion()
                            }
                        }
                    }
                }
            }
            audioView.isVisible =
                exerciseFillGap.isAudioAvailable == "1" && !exerciseFillGap.audio.isNullOrEmpty()
            audioView.setOnClickListener {
                audioView.playAudio(exerciseFillGap.audio) {
                    (activity as? HomeActivity)?.showMessage(getString(R.string.audio_not_found))
                }
            }
            imageViewInfo.isVisible = !exerciseFillGap.notes.isNullOrEmpty()
            imageViewInfo.setOnClickListener {
                findNavController().navigate(
                    ExerciseFragmentDirections.toNotesDialogFragment(
                        exerciseFillGap.notes ?: ""
                    )
                )
            }
            textViewHint.setOnClickListener {
                showHintAnswer(exerciseFillGap.options.toString())
            }
        }
        return binding.root
    }

    /*override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)
        activity?.window?.setSoftInputMode(WindowManager.LayoutParams.SOFT_INPUT_ADJUST_PAN)
    }

    override fun onDestroyView() {
        activity?.window?.setSoftInputMode(WindowManager.LayoutParams.SOFT_INPUT_ADJUST_RESIZE)
        super.onDestroyView()
    }*/

    private fun createQuestionString(text: String): String {
        var wordToReplace = ""
        exerciseFillGap.options?.toCharArray()?.forEachIndexed { index, c ->
            if (index < text.length) {
                wordToReplace += c
            } else {
                wordToReplace += "_"
            }
        }
        return exerciseFillGap.question?.replace("...", wordToReplace).toString()
    }

    private fun showHintError() {
        binding.textViewHint.isVisible = true
        binding.textViewHint.text = getString(R.string.show_correct_answer)
    }

    private fun showHintAnswer(answer: String) {
        binding.textViewHint.text = answer
    }

    private fun animateWrongAnswer() {
        if (!isAnimating) {
            val animationDuration = resources.getInteger(R.integer.animation_duration)
            (binding.editTextAnswer.background as? TransitionDrawable)?.startTransition(
                animationDuration / 2
            )
            binding.editTextAnswer.postDelayed({
                (binding.editTextAnswer.background as? TransitionDrawable)?.reverseTransition(
                    animationDuration / 2
                )
            }, animationDuration / 2L)
        }
    }
}

class FillGapPagerAdapter(
    private val exerciseFillGaps: List<ExerciseFillGap>,
    fragment: Fragment
) : FragmentStateAdapter(fragment) {
    override fun getItemCount(): Int = exerciseFillGaps.size

    override fun createFragment(position: Int): Fragment = FillGapExerciseFragment()
        .apply {
            this.exerciseFillGap = exerciseFillGaps[position]
        }
}
