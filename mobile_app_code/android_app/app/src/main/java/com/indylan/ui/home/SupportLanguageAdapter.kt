package com.indylan.ui.home

import android.view.LayoutInflater
import android.view.ViewGroup
import androidx.lifecycle.LifecycleOwner
import androidx.recyclerview.widget.DiffUtil
import androidx.recyclerview.widget.ListAdapter
import androidx.recyclerview.widget.RecyclerView
import com.indylan.data.model.SupportLanguage
import com.indylan.databinding.ItemLanguageBinding
import java.util.*

class SupportLanguageAdapter(
    private val lifecycleOwner: LifecycleOwner,
    private val callback: (SupportLanguage) -> Unit
) : ListAdapter<SupportLanguage, LanguageViewHolder>(SupportLanguageDiff) {

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): LanguageViewHolder {
        val binding =
            ItemLanguageBinding.inflate(LayoutInflater.from(parent.context), parent, false)
                .apply {
                    constraintLayoutLanguage.setOnClickListener {
                        language?.let {
                            callback.invoke(it)
                        }
                    }
                }
        return LanguageViewHolder(binding, lifecycleOwner)
    }

    override fun onBindViewHolder(holder: LanguageViewHolder, position: Int) {
        holder.bind(getItem(position))
    }
}

class LanguageViewHolder(
    private val binding: ItemLanguageBinding,
    private val lifecycleOwner: LifecycleOwner
) : RecyclerView.ViewHolder(binding.root) {

    fun bind(language: SupportLanguage) {
        binding.language = language
        binding.lifecycleOwner = lifecycleOwner
        binding.executePendingBindings()
        binding.textViewLanguageName.text = language.name?.replaceFirstChar {
            if (it.isLowerCase()) it.titlecase(
                Locale.getDefault()
            ) else it.toString()
        }
    }
}

object SupportLanguageDiff : DiffUtil.ItemCallback<SupportLanguage>() {
    override fun areItemsTheSame(oldItem: SupportLanguage, newItem: SupportLanguage): Boolean {
        return oldItem.id == newItem.id
    }

    override fun areContentsTheSame(oldItem: SupportLanguage, newItem: SupportLanguage): Boolean {
        return oldItem == newItem
    }
}