package com.indylan.ui.home

import android.content.Context
import android.graphics.drawable.Drawable
import android.view.LayoutInflater
import android.view.ViewGroup
import androidx.lifecycle.LifecycleOwner
import androidx.recyclerview.widget.DiffUtil
import androidx.recyclerview.widget.ListAdapter
import androidx.recyclerview.widget.RecyclerView
import com.bumptech.glide.load.DataSource
import com.bumptech.glide.load.engine.GlideException
import com.bumptech.glide.request.RequestListener
import com.bumptech.glide.request.target.Target
import com.indylan.common.extensions.goneView
import com.indylan.common.glide.GlideApp
import com.indylan.data.model.Language
import com.indylan.databinding.ItemLanguageFlagBinding
import java.util.*

class LanguageFlagAdapter(
    private val context: Context,
    private val lifecycleOwner: LifecycleOwner,
    private val callback: (Language) -> Unit
) : ListAdapter<Language, LanguageFlagViewHolder>(LanguageDiff) {

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): LanguageFlagViewHolder {
        val binding =
            ItemLanguageFlagBinding.inflate(LayoutInflater.from(parent.context), parent, false)
                .apply {
                    root.layoutDirection = context.resources.configuration.layoutDirection
                    constraintLayoutLanguage.setOnClickListener {
                        language?.let {
                            callback.invoke(it)
                        }
                    }
                }
        return LanguageFlagViewHolder(binding, lifecycleOwner)
    }

    override fun onBindViewHolder(holder: LanguageFlagViewHolder, position: Int) {
        holder.bind(getItem(position))
    }
}

class LanguageFlagViewHolder(
    private val binding: ItemLanguageFlagBinding,
    private val lifecycleOwner: LifecycleOwner
) : RecyclerView.ViewHolder(binding.root) {

    fun bind(language: Language) {
        binding.language = language
        binding.lifecycleOwner = lifecycleOwner
        binding.executePendingBindings()
        binding.textViewLanguageName.text = language.name?.replaceFirstChar {
            if (it.isLowerCase()) it.titlecase(
                Locale.getDefault()
            ) else it.toString()
        }
        GlideApp.with(binding.root.context)
            .load(language.image)
            /*.load(
                binding.root.resources.getIdentifier(
                    "flag_${language.correctCountryCode()}",
                    "drawable",
                    binding.root.context.packageName
                )
            )*/
            .listener(object : RequestListener<Drawable> {
                override fun onLoadFailed(
                    e: GlideException?,
                    model: Any?,
                    target: Target<Drawable>?,
                    isFirstResource: Boolean
                ): Boolean {
                    binding.cardLanguageFlag.goneView()
                    return false
                }

                override fun onResourceReady(
                    resource: Drawable?,
                    model: Any?,
                    target: Target<Drawable>?,
                    dataSource: DataSource?,
                    isFirstResource: Boolean
                ): Boolean {
                    binding.progressBar.goneView()
                    return false
                }

            })
            .into(binding.imageViewLanguageFlag)
    }
}

object LanguageDiff : DiffUtil.ItemCallback<Language>() {
    override fun areItemsTheSame(oldItem: Language, newItem: Language): Boolean {
        return oldItem.id == newItem.id
    }

    override fun areContentsTheSame(oldItem: Language, newItem: Language): Boolean {
        return oldItem == newItem
    }
}