package com.indylan.ui.home.exercise

import android.animation.AnimatorInflater
import android.graphics.drawable.TransitionDrawable
import android.os.Bundle
import android.text.InputFilter
import android.text.InputFilter.LengthFilter
import android.text.InputType.TYPE_NULL
import android.text.method.DigitsKeyListener
import android.view.Gravity
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.appcompat.widget.AppCompatEditText
import androidx.core.view.*
import androidx.fragment.app.Fragment
import androidx.viewpager2.adapter.FragmentStateAdapter
import com.google.android.flexbox.FlexboxLayout
import com.google.android.material.textview.MaterialTextView
import com.indylan.R
import com.indylan.common.extensions.hideView
import com.indylan.common.extensions.px
import com.indylan.common.glide.GlideApp
import com.indylan.data.model.ExerciseSingleAnswer
import com.indylan.databinding.FragmentExerciseChooseLetterBinding
import com.indylan.ui.home.ExerciseFragment
import com.indylan.ui.home.HomeActivity
import com.indylan.ui.home.exercise.base.BaseExerciseFragment

class ChooseLettersFragment : BaseExerciseFragment() {

    lateinit var exercise: ExerciseSingleAnswer
    lateinit var binding: FragmentExerciseChooseLetterBinding
    private var answeredWrong = false
    private val animationDuration by lazy {
        resources.getInteger(R.integer.animation_duration)
    }

    override fun onFocusGained() {
        view?.doOnLayout {
            (parentFragment as ExerciseFragment).showExerciseTypeName()
        }
    }

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View {
        binding = FragmentExerciseChooseLetterBinding.inflate(inflater, container, false).apply {
            lifecycleOwner = viewLifecycleOwner
            audioView.isVisible =
                exercise.isAudioAvailable == "1" && !exercise.audio.isNullOrEmpty()
            audioView.setOnClickListener {
                audioView.playAudio(exercise.audio) {
                    (activity as? HomeActivity)?.showMessage(getString(R.string.audio_not_found))
                }
            }
        }
        return binding.root
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)
        GlideApp.with(this).load(exercise.image).into(binding.imageView)
        createQuestionViews()
        createAnswerViews()
    }

    private fun createQuestionViews() {
        exercise.word?.toCharArray()?.forEach {
            val view = createBlankEditText(it)
            binding.flexBoxBlanks.addView(view)
        }
    }

    private fun createBlankEditText(character: Char): View {
        val editText = AppCompatEditText(requireContext())
        editText.maxLines = 1
        editText.filters = arrayOf<InputFilter>(LengthFilter(2))
        editText.gravity = Gravity.CENTER
        editText.keyListener = DigitsKeyListener.getInstance(character.toString())
        editText.isFocusable = false
        editText.isClickable = false
        editText.setTextAppearance(requireContext(), R.style.TextAppearance_App_Subtitle1)
        editText.inputType = TYPE_NULL
        editText.tag = character.toString()
        val layoutParams = FlexboxLayout.LayoutParams(40.px, 40.px)
        editText.layoutParams = layoutParams
        return editText
    }

    private fun createAnswerViews() {
        exercise.word?.toCharArray()?.asList()?.shuffled()?.forEach {
            val view = createTextView(it)
            binding.flexBoxOptions.addView(view)
        }
    }

    private fun createTextView(character: Char): View {
        val textView = MaterialTextView(requireContext())
        textView.text = character.toString()
        textView.gravity = Gravity.CENTER
        textView.isClickable = true
        textView.isFocusable = true
        textView.setBackgroundResource(R.drawable.bg_wrong_answer_transition)
        textView.setTextAppearance(requireContext(), R.style.TextAppearance_App_Subtitle1)
        val layoutParams = FlexboxLayout.LayoutParams(40.px, 40.px)
        layoutParams.setMargins(10.px)
        textView.layoutParams = layoutParams
        ViewCompat.setElevation(textView, 4f)
        textView.stateListAnimator =
            AnimatorInflater.loadStateListAnimator(requireContext(), R.animator.selection)
        textView.setOnClickListener(::checkText)
        textView.tag = character.toString()
        return textView
    }

    private fun checkText(textView: View) {
        val letter = ((textView as MaterialTextView).tag as String)
        val questionEditTexts = binding.flexBoxBlanks.children.toList()
        characters@ for (i in 0..questionEditTexts.size) {
            val editText = questionEditTexts[i]
            if (editText is AppCompatEditText) {
                if (editText.text.toString().isEmpty()) {
                    if (letter == editText.tag as String) {
                        editText.setText(letter)
                        textView.hideView()
                        if (i == questionEditTexts.size - 1) {
                            if (!answeredWrong) {
                                (parentFragment as? ExerciseFragment)?.increaseScore()
                            }
                            (parentFragment as? ExerciseFragment)?.nextQuestion()
                        }
                        break@characters
                    } else {
                        answeredWrong = true
                        showIncorrectAnswer(textView)
                        return
                    }
                }
            }
        }
    }

    private fun showIncorrectAnswer(view: View) {
        (view.background as? TransitionDrawable)?.startTransition(animationDuration / 2)
        view.postDelayed({
            (view.background as? TransitionDrawable)?.reverseTransition(animationDuration / 2)
        }, animationDuration / 2L)
    }
}

class ChooseLettersPagerAdapter(
    private val exercises: List<ExerciseSingleAnswer>,
    fragment: Fragment
) : FragmentStateAdapter(fragment) {
    override fun getItemCount(): Int = exercises.size

    override fun createFragment(position: Int): Fragment = ChooseLettersFragment()
        .apply {
            this.exercise = exercises[position]
        }
}