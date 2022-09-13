//
//  ExType10VC.swift
//  Indylan
//
//  Created by Bhavik Thummar on 10/5/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit
import AVFoundation

class ExType10VC: ExerciseController, UITableViewDataSource, UITableViewDelegate, AVAudioPlayerDelegate {
    
    @IBOutlet var lblQuestion: UILabel!
    
    @IBOutlet weak var btnAudio: UIButton!
    @IBOutlet weak var btnNote: ThemeButton!
    
    @IBOutlet var tblViewOptions: IntrinsicTableView!
    
    @IBOutlet var btnChooseOption: UIButton!

    @IBOutlet var scrView: UIScrollView!
    
    var indicator : UIActivityIndicatorView!
    
    var audioPlayer: AVPlayer!
    
    var playerItem: AVPlayerItem!
    
    var arrType10Questions = Array<JSON>()
    
    var urlString = ""
    
    var questionIndex = 0
    
    var score = 0
    
    var attempts = 0

    override func viewDidLoad() {
        
        super.viewDidLoad()
        
        audioPlayer = nil
        
        self.automaticallyAdjustsScrollViewInsets = false

        if (UserDefaults.standard.object(forKey: "type10ScrollAlert")) == nil
        {
            UserDefaults.standard.set( true, forKey: "type10ScrollAlert")
            UserDefaults.standard.synchronize()

            Alert.showWith("", message: "Scroll down to see all language options".localized(), completion: nil)
        }
        
        self.title = selectedCategory

        lblQuestion.textColor = Colors.black
        lblQuestion.font = Fonts.centuryGothic(ofType: .bold, withSize: 14)
        
        btnChooseOption.titleLabel?.font = Fonts.centuryGothic(ofType: .bold, withSize: 16)
        
        tblViewOptions.estimatedRowHeight = 70
        tblViewOptions.rowHeight = UITableViewAutomaticDimension
        tblViewOptions.register(UINib(nibName: "CellExType1", bundle: nil), forCellReuseIdentifier: "cellExType1")
        
        btnChooseOption.titleLabel?.font = Fonts.centuryGothic(ofType: .bold, withSize: 12)
        btnChooseOption.layer.borderColor = Colors.border.cgColor
        btnChooseOption.layer.borderWidth = 1
        btnChooseOption.layer.cornerRadius = 8
        view.bringSubview(toFront: btnChooseOption)
        
        btnNote.layer.cornerRadius = 4
        btnNote.tintColor = Colors.white
        
        self.updateQuestion()
    }

    func updateQuestion() {
        
        if audioPlayer != nil {
            indicator.stopAnimating()
            audioPlayer.pause()
        }
        
        self.view.isUserInteractionEnabled = true
        
        attempts = 0
        
        btnChooseOption.setTitleColor(Colors.black, for: .normal)
        btnChooseOption.backgroundColor = Colors.white
        btnChooseOption.setTitle("chooseOption".localized(), for: UIControlState.normal)
        
        lblQuestion.text = "\(arrType10Questions[questionIndex]["word"].stringValue)"
        tblViewOptions.estimatedRowHeight = 50
        tblViewOptions.rowHeight = UITableViewAutomaticDimension
        
        tblViewOptions.reloadData()
        tblViewOptions.scrollToRow(at: IndexPath.init(row: 0, section: 0), at: .top, animated: true)
        
        if (arrType10Questions[questionIndex]["audio_file"].stringValue.isEmpty)
        {
            btnAudio.isHidden = true
        }
        else
        {
            btnAudio.isHidden = false
            urlString = arrType10Questions[questionIndex]["audio_file"].stringValue
        }
        
        if (arrType10Questions[questionIndex]["notes"].stringValue.isEmpty)
        {
            btnNote.isHidden = true
        }
        else
        {
            btnNote.isHidden = false
        }
    }
    
    
    @IBAction func btnNoteAction(_ sender: ThemeButton) {
        let note = arrType10Questions[questionIndex]["notes"].stringValue
        guard !note.isEmpty else { return }
        Alert.showWith(message: note, completion: nil)
    }
    
    @IBAction func btnAudioAction(_ sender: UIButton) {
        let url =  URL(string: urlString as String)
        
        if (url != nil) {
            
            if audioPlayer != nil {
                audioPlayer.removeObserver(self, forKeyPath: "status")
            }
            
            playerItem = AVPlayerItem(url: url!)

            indicator = UIActivityIndicatorView(activityIndicatorStyle: .white)
            indicator.center = sender.center
            sender.superview?.addSubview(indicator)
            
            DispatchQueue.main.asyncAfter(deadline: .now() + 0.0) {
                self.btnAudio.isUserInteractionEnabled = false
                self.indicator.startAnimating()
            }
            
            audioPlayer = AVPlayer(playerItem: playerItem)
            audioPlayer.addObserver( self, forKeyPath:"status", options:.initial, context:nil)
            
            audioPlayer.play()
            
        } else {
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
    
    // MARK: UITABLEVIEW DELEGATE METHODS

    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        arrType10Questions[questionIndex]["option"].count
    }

    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        
        let cellOptions = tblViewOptions.dequeueReusableCell(withIdentifier: "cellExType1") as! CellExType1
        
        cellOptions.optionView.state = .normal
        
        cellOptions.lblOption.text = "\(arrType10Questions[questionIndex]["option"][indexPath.row]["word"].stringValue)"
        
        return cellOptions
    }
    
    func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath)
    {
        let cellOptions = tblViewOptions.cellForRow(at: indexPath) as! CellExType1
        
        attempts += 1
        
        if arrType10Questions[questionIndex]["option"][indexPath.row]["is_correct"].intValue == 1
        {
            if attempts == 1 {
                score += 1
            }
            
            TapticEngine.notification.feedback(.success)
            
            self.view.isUserInteractionEnabled = false
            
            UIView.animate(withDuration: 0.3, delay: 0.0, animations: {
                cellOptions.optionView.state = .green
            },  completion: nil)
            
            UIView.transition(with: btnChooseOption, duration: 0.3, options: .transitionCrossDissolve, animations: {
                self.btnChooseOption.backgroundColor = Colors.green
                self.btnChooseOption.setTitleColor(Colors.white, for: .normal)
                self.btnChooseOption.setTitle("correct".localized(), for: .normal)
            }, completion: { _ in
                
                DispatchQueue.main.asyncAfter(deadline: .now() + 0.5) {
                    
                    self.questionIndex += 1
                    
                    self.view.isUserInteractionEnabled = true
                    
                    if (self.arrType10Questions.count > self.questionIndex)
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
                        objExComplete.score = self.score
                        objExComplete.totalQuestions = self.arrType10Questions.count
                        self.navigationController?.pushViewController(objExComplete, animated: true)
                    }
                }
            })
        }
        else
        {
            TapticEngine.notification.feedback(.error)
            
            cellOptions.shake()
            
            self.view.isUserInteractionEnabled = false
            
            UIView.transition(with: btnChooseOption, duration: 0.3, options: .transitionCrossDissolve, animations: {
                self.btnChooseOption.backgroundColor = Colors.white
                self.btnChooseOption.setTitleColor(Colors.red, for: .normal)
                self.btnChooseOption.setTitle("retry".localized(), for: .normal)
            }, completion: { _ in
                
                DispatchQueue.main.asyncAfter(deadline: .now() + 0.5) {
                    
                    UIView.transition(with: self.btnChooseOption, duration: 0.3, options: .transitionCrossDissolve, animations: {
                        self.btnChooseOption.backgroundColor = Colors.white
                        self.btnChooseOption.setTitleColor(Colors.black, for: .normal)
                        self.btnChooseOption.setTitle("chooseOption".localized(), for: .normal)
                    }, completion: { _ in
                        self.view.isUserInteractionEnabled = true
                    })
                }
            })
            
            UIView.animate(withDuration: 0.3, delay: 0.0, animations: {
                cellOptions.optionView.state = .red
            },  completion: { (Bool) -> Void in
                
                DispatchQueue.main.asyncAfter(deadline: .now() + 0.5) {
                    UIView.animate(withDuration: 0.3, delay: 0.0, animations: {
                        cellOptions.optionView.state = .normal
                    })
                }
            })
        }
    }
    
    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
}
