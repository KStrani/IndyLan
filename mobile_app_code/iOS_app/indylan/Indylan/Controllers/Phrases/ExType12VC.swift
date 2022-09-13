//
//  ExType12VC.swift
//  Indylan
//
//  Created by Bhavik Thummar on 10/05/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit
import AVFoundation

class ExType12VC: ExerciseController {

    @IBOutlet var btnAudio: UIButton!

    @IBOutlet var scrView: UIScrollView!
    
    @IBOutlet var lblQuestion: UILabel!
    
    @IBOutlet weak var lblTranslation: UILabel!
    @IBOutlet weak var btnSeeTranslation: BaseButton!
    
    @IBOutlet var btnContinue: BaseButton!
    
    var indicator : UIActivityIndicatorView!
    
    var audioPlayer: AVPlayer!
    
    var playerItem: AVPlayerItem!
    
    var urlString = "" as String
    
    var arrType12Questions = Array<JSON>()
    
    var questionIndex = 0
    
    override func viewDidLoad() {
        
        super.viewDidLoad()

        self.title = selectedCategory
        
        self.automaticallyAdjustsScrollViewInsets = false

        lblQuestion.textColor = Colors.black
        lblQuestion.font = Fonts.centuryGothic(ofType: .bold, withSize: 14)
        
        lblTranslation.isHidden = true
        lblTranslation.textColor = Colors.black
        lblTranslation.font = Fonts.centuryGothic(ofType: .regular, withSize: 14)
        
        btnSeeTranslation.layer.borderWidth = themeBorderWidth
        btnSeeTranslation.layer.borderColor = Colors.red.cgColor
        btnSeeTranslation.titleLabel?.font = Fonts.centuryGothic(ofType: .regular, withSize: 14)
        btnSeeTranslation.setTitle("seeTranslation".localized(), for: .normal)
        btnSeeTranslation.setTitleColor(Colors.black, for: .normal)
        
        btnContinue.layer.cornerRadius = 8
        btnContinue.layer.borderColor = Colors.border.cgColor
        btnContinue.layer.borderWidth = 1
        btnContinue.setTitleColor(Colors.black, for: .normal)
        btnContinue.backgroundColor = Colors.white
        view.bringSubview(toFront: btnContinue)
        
        self.updateQuestion()
    }

//MARK: FUNCTIONS
    
    func updateQuestion()
    {
        if audioPlayer != nil
        {
            indicator.stopAnimating()
            
            audioPlayer.pause()
        }
        
        if (arrType12Questions[questionIndex]["audio_file"].stringValue.isEmpty)
        {
            btnAudio.isHidden = true
        }
        else
        {
            btnAudio.isHidden = false
            urlString = arrType12Questions[questionIndex]["audio_file"].stringValue
        }
        
        self.view.isUserInteractionEnabled = true
        
        lblTranslation.text = ""
        lblTranslation.isHidden = true
        
        btnContinue.setTitle("tapContinue".localized(), for: .normal)
        
        UIView.transition(with: btnSeeTranslation, duration: 0.3, options: .transitionCrossDissolve, animations: {
            self.btnSeeTranslation.setTitle("seeTranslation".localized(), for: .normal)
        }, completion: { _ in
            self.btnSeeTranslation.isUserInteractionEnabled = true
        })
        
        lblQuestion.text = "\(arrType12Questions[questionIndex]["word"].stringValue)"
    }
    
//MARK: BUTTON ACTIONS
    
    @IBAction func btnTranslationClicked(_ sender: Any) {
        btnSeeTranslation.isUserInteractionEnabled = false
        lblTranslation.isHidden = false
                
        let translation = self.arrType12Questions[self.questionIndex]["option"].stringValue
        
        UIView.transition(with: btnSeeTranslation, duration: 0.3, options: .transitionCrossDissolve, animations: {
            self.btnSeeTranslation.setTitle("", for: .normal)
        }, completion: { _ in
            UIView.transition(with: self.lblTranslation, duration: 0.3, options: .transitionCrossDissolve, animations: {
                self.lblTranslation.text = translation
            }, completion: nil)
        })

        UIView.transition(with: btnContinue, duration: 0.3, options: .transitionCrossDissolve, animations: {
            self.btnContinue.setTitle("next".localized(), for: .normal)
        }, completion: nil)
    }
    
    
    @IBAction func btnContinueClicked(_ sender: Any)
    {
        questionIndex += 1
        if arrType12Questions.count > questionIndex
        {
            UIView.animate(withDuration: 0.5, animations: {
                let animation = CATransition()
                animation.duration = 1.0
                animation.startProgress = 0.0
                animation.endProgress = 1.0
                animation.timingFunction = CAMediaTimingFunction(name: kCAMediaTimingFunctionEaseOut)
                animation.type = "pageCurl"
                animation.subtype = "fromBottom"
                animation.isRemovedOnCompletion = false
                animation.fillMode = "extended"
                self.view.layer.add(animation, forKey: "pageFlipAnimation")
            })
            self.updateQuestion()
        }
        else
        {
            let objExComplete = UIStoryboard.exercise.instantiateViewController(withClass: CongratsVC.self)!
            objExComplete.totalQuestions = self.arrType12Questions.count
            self.navigationController?.pushViewController(objExComplete, animated: true)
        }
    }
    
    @IBAction func btnAudioClicked(_ sender: Any) {
        
        let url =  URL(string: urlString as String)
        
        if (url != nil)
        {
            if audioPlayer != nil
            {
                audioPlayer.removeObserver(self, forKeyPath: "status")
            }
            
            playerItem = AVPlayerItem(url: url!)
            
            indicator = UIActivityIndicatorView(activityIndicatorStyle: .white)
            indicator.center = btnAudio.center
            view.addSubview(indicator)
            
            DispatchQueue.main.asyncAfter(deadline: .now() + 0.0) {
                self.btnAudio.isUserInteractionEnabled = false
                self.indicator.startAnimating()
            }
            
            audioPlayer = AVPlayer(playerItem:playerItem)
            audioPlayer.addObserver( self, forKeyPath:"status", options:.initial, context:nil)
            
            audioPlayer.play()
        }
        else
        {
            SnackBar.show("Audio error")
        }
    }
    
    override func observeValue(forKeyPath keyPath: String?, of object: Any?, change: [NSKeyValueChangeKey : Any]?, context: UnsafeMutableRawPointer?) {
        if keyPath == "status" {
            indicator.stopAnimating()
            btnAudio.isUserInteractionEnabled = true
        }
    }
    
    override func viewWillDisappear(_ animated: Bool) {
        super.viewWillDisappear(animated)
        
        if audioPlayer != nil {
            audioPlayer.removeObserver(self, forKeyPath: "status")
        }
    }
    
    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
}
