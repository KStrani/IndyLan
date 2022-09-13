package com.indylan.ui.home.exercise

import android.os.Bundle
import android.text.Editable
import android.text.InputFilter
import android.text.InputFilter.LengthFilter
import android.text.InputType.TYPE_TEXT_FLAG_NO_SUGGESTIONS
import android.text.TextWatcher
import android.text.method.DigitsKeyListener
import android.view.Gravity
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.appcompat.widget.AppCompatEditText
import androidx.core.content.ContextCompat
import androidx.core.view.doOnLayout
import androidx.core.view.isVisible
import androidx.fragment.app.Fragment
import androidx.viewpager2.adapter.FragmentStateAdapter
import com.google.android.flexbox.FlexboxLayout
import com.indylan.R
import com.indylan.common.extensions.px
import com.indylan.common.extensions.showKeyBoard
import com.indylan.data.model.ExerciseSingleAnswer
import com.indylan.databinding.FragmentExerciseListeningBinding
import com.indylan.ui.home.ExerciseFragment
import com.indylan.ui.home.HomeActivity
import com.indylan.ui.home.exercise.base.BaseExerciseFragment

class ListeningFragment : BaseExerciseFragment() {

    lateinit var binding: FragmentExerciseListeningBinding
    lateinit var exercise: ExerciseSingleAnswer
    private var answeredWrong = false
    private var wrongCharacterCount = 0

    override fun onFocusGained() {
        view?.doOnLayout {
            enableEditText(binding.flexBoxBlanks.getChildAt(0) as AppCompatEditText)
            (parentFragment as ExerciseFragment).showExerciseTypeName()
        }
    }

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View {
        binding = FragmentExerciseListeningBinding.inflate(inflater, container, false).apply {
            lifecycleOwner = viewLifecycleOwner
            audioView.setOnClickListener {
                audioView.playAudio(exercise.audio) {
                    (activity as? HomeActivity)?.showMessage(getString(R.string.audio_not_found))
                }
            }
            textViewHint.setOnClickListener {
                showHintAnswer(exercise.word.toString())
            }
        }
        return binding.root
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)
        createQuestionViews()
    }

    private fun enableEditText(editText: AppCompatEditText) {
        editText.isEnabled = true
        editText.requestFocus()
        showKeyBoard()
    }

    private fun disableEditText(editText: AppCompatEditText) {
        editText.isEnabled = false
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
        editText.filters = arrayOf<InputFilter>(LengthFilter(1))
        editText.gravity = Gravity.CENTER
        editText.keyListener = DigitsKeyListener.getInstance(character.toString())
        editText.isClickable = false
        //editText.isFocusableInTouchMode = false
        editText.setTextAppearance(requireContext(), R.style.TextAppearance_App_Subtitle1)
        editText.inputType = TYPE_TEXT_FLAG_NO_SUGGESTIONS
        editText.tag = character.toString()
        val layoutParams = FlexboxLayout.LayoutParams(40.px, 40.px)
        editText.layoutParams = layoutParams
        val textWatcher: TextWatcher = object : TextWatcher {
            override fun afterTextChanged(s: Editable?) {

            }

            override fun beforeTextChanged(s: CharSequence?, start: Int, count: Int, after: Int) {
                editText.setTextColor(
                    ContextCompat.getColor(
                        requireContext(),
                        android.R.color.black
                    )
                )
            }

            override fun onTextChanged(s: CharSequence?, start: Int, before: Int, count: Int) {
                if (!s.toString().equals(editText.tag as String?, ignoreCase = true)) {
                    editText.removeTextChangedListener(this)
                    editText.setTextColor(
                        ContextCompat.getColor(
                            requireContext(),
                            R.color.app_red_400
                        )
                    )
                    editText.postDelayed({
                        editText.text = null
                        editText.addTextChangedListener(this)
                    }, 500)
                    answeredWrong = true
                    wrongCharacterCount++
                    if (wrongCharacterCount >= 5) {
                        showHintError()
                    }
                } else {
                    checkText(editText)
                }
            }
        }
        editText.addTextChangedListener(textWatcher)
        editText.isEnabled = false
        return editText
    }

    private fun checkText(editText: AppCompatEditText) {
        val currentIndex = binding.flexBoxBlanks.indexOfChild(editText)
        if (currentIndex < binding.flexBoxBlanks.childCount - 1) {
            enableEditText(binding.flexBoxBlanks.getChildAt(currentIndex + 1) as AppCompatEditText)
            disableEditText(editText)
        } else {
            disableEditText(editText)
            if (!answeredWrong) {
                (parentFragment as? ExerciseFragment)?.increaseScore()
            }
            (parentFragment as? ExerciseFragment)?.nextQuestion()
        }
    }

    private fun showHintError() {
        binding.textViewHint.isVisible = true
        binding.textViewHint.text = getString(R.string.show_correct_answer)
    }

    private fun showHintAnswer(answer: String) {
        binding.textViewHint.text = answer
    }
}

class ListeningPagerAdapter(
    private val exercises: List<ExerciseSingleAnswer>,
    fragment: Fragment
) : FragmentStateAdapter(fragment) {
    override fun getItemCount(): Int = exercises.size

    override fun createFragment(position: Int): Fragment = ListeningFragment()
        .apply {
            this.exercise = exercises[position]
        }
}