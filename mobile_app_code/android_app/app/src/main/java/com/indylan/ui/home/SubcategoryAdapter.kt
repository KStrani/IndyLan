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
import com.indylan.common.extensions.isValidImageFile
import com.indylan.common.extensions.showView
import com.indylan.common.glide.GlideApp
import com.indylan.data.model.Subcategory
import com.indylan.databinding.ItemSubcategoryBinding

class SubcategoryAdapter(
    private val context: Context,
    private val lifecycleOwner: LifecycleOwner,
    private val callback: (Subcategory) -> Unit
) : ListAdapter<Subcategory, SubcategoryFlagViewHolder>(SubcategoryDiff) {

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): SubcategoryFlagViewHolder {
        val binding =
            ItemSubcategoryBinding.inflate(LayoutInflater.from(parent.context), parent, false)
                .apply {
                    root.layoutDirection = context.resources.configuration.layoutDirection
                    constraintLayoutCategory.setOnClickListener {
                        subcategory?.let {
                            callback.invoke(it)
                        }
                    }
                }
        return SubcategoryFlagViewHolder(binding, lifecycleOwner)
    }

    override fun onBindViewHolder(holder: SubcategoryFlagViewHolder, position: Int) {
        holder.bind(getItem(position))
    }
}

class SubcategoryFlagViewHolder(
    private val binding: ItemSubcategoryBinding,
    private val lifecycleOwner: LifecycleOwner
) : RecyclerView.ViewHolder(binding.root) {

    fun bind(subcategory: Subcategory) {
        binding.subcategory = subcategory
        binding.lifecycleOwner = lifecycleOwner
        binding.executePendingBindings()
        if (subcategory.image?.isValidImageFile() == true) {
            binding.progressBar.showView()
            binding.cardCategory.showView()
            GlideApp.with(binding.root.context)
                .load(subcategory.image)
                .listener(object : RequestListener<Drawable> {
                    override fun onLoadFailed(
                        e: GlideException?,
                        model: Any?,
                        target: Target<Drawable>?,
                        isFirstResource: Boolean
                    ): Boolean {
                        binding.cardCategory.goneView()
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
                .into(binding.imageViewCategory)
        } else {
            binding.cardCategory.goneView()
        }
    }
}

object SubcategoryDiff : DiffUtil.ItemCallback<Subcategory>() {
    override fun areItemsTheSame(oldItem: Subcategory, newItem: Subcategory): Boolean {
        return oldItem.id == newItem.id
    }

    override fun areContentsTheSame(oldItem: Subcategory, newItem: Subcategory): Boolean {
        return oldItem == newItem
    }
}